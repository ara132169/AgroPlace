<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SellerPaymentAccount;
use App\Models\ManualDeposit;

class PaymentAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Mostrar cuentas de pago del vendedor
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();
        $paymentAccounts = $seller->paymentAccounts()->orderBy('created_at', 'desc')->get();
        
        return view('back.pages.tienda.payment-accounts', compact('paymentAccounts'));
    }

    /**
     * Registrar nueva cuenta de pago
     */
    public function store(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        // Validación base
        $validator = Validator::make($request->all(), [
            'account_type' => 'required|in:debit_card,bank_account,paypal',
            'account_holder_name' => 'required|string|max:255',
        ]);

        // Validación específica por tipo
        if ($request->account_type === 'debit_card') {
            $validator->sometimes(['card_last_four'], 'required|string|size:4', function() {
                return true;
            });
        } elseif ($request->account_type === 'bank_account') {
            $validator->sometimes(['bank_name'], 'required|string', function() {
                return true;
            });
        } elseif ($request->account_type === 'paypal') {
            $validator->sometimes(['paypal_email'], 'required|email', function() {
                return true;
            });
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'seller_id' => $seller->id,
                'account_type' => $request->account_type,
                'account_holder_name' => $request->account_holder_name,
            ];

            // Datos específicos por tipo
            if ($request->account_type === 'debit_card') {
                $data['card_last_four'] = $request->card_last_four;
                $data['card_brand'] = $request->card_brand;
                $data['card_token'] = $request->card_token; // Se encripta automáticamente en el modelo
            } elseif ($request->account_type === 'bank_account') {
                $data['bank_name'] = $request->bank_name;
                $data['clabe'] = $request->clabe;
                if ($request->clabe) {
                    $data['account_number_last_four'] = substr($request->clabe, -4);
                }
            } elseif ($request->account_type === 'paypal') {
                $data['paypal_email'] = $request->paypal_email;
            }

            $account = SellerPaymentAccount::create($data);

            // Log para admin
            \Log::info("Nueva cuenta de pago registrada", [
                'seller_id' => $seller->id,
                'seller_name' => $seller->name,
                'account_type' => $request->account_type,
                'account_id' => $account->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cuenta registrada exitosamente. Será verificada por un administrador.',
                'account' => $account
            ]);

        } catch (\Exception $e) {
            \Log::error('Error registrando cuenta de pago: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Eliminar cuenta de pago (solo si no está verificada)
     */
    public function destroy($id)
    {
        $seller = Auth::guard('seller')->user();
        $account = SellerPaymentAccount::where('seller_id', $seller->id)
                                      ->where('id', $id)
                                      ->first();

        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta no encontrada'
            ], 404);
        }

        if ($account->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una cuenta verificada'
            ], 400);
        }

        $account->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cuenta eliminada'
        ]);
    }

    /**
     * Mostrar historial de depósitos
     */
    public function deposits()
    {
        $seller = Auth::guard('seller')->user();
        $deposits = ManualDeposit::forSeller($seller->id)
                                ->with(['paymentAccount', 'order'])
                                ->orderBy('requested_at', 'desc')
                                ->paginate(20);

        $stats = [
            'pending_amount' => ManualDeposit::forSeller($seller->id)->pending()->sum('amount'),
            'completed_amount' => ManualDeposit::forSeller($seller->id)->where('status', 'completed')->sum('amount'),
            'total_deposits' => ManualDeposit::forSeller($seller->id)->count()
        ];

        return view('back.pages.tienda.deposit-history', compact('deposits', 'stats'));
    }

    /**
     * Solicitar depósito manual
     */
    public function requestDeposit(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $validator = Validator::make($request->all(), [
            'payment_account_id' => 'required|exists:seller_payment_accounts,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:500',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que la cuenta pertenezca al vendedor y esté verificada
        $paymentAccount = SellerPaymentAccount::where('seller_id', $seller->id)
                                            ->where('id', $request->payment_account_id)
                                            ->verified()
                                            ->first();

        if (!$paymentAccount) {
            return response()->json([
                'success' => false,
                'message' => 'Cuenta de pago no válida o no verificada'
            ], 400);
        }

        try {
            $deposit = ManualDeposit::create([
                'seller_id' => $seller->id,
                'payment_account_id' => $request->payment_account_id,
                'amount' => $request->amount,
                'currency' => 'MXN',
                'description' => $request->description,
                'order_id' => $request->order_id,
                'status' => 'pending'
            ]);

            // Notificar admin
            \Log::info("Nueva solicitud de depósito", [
                'deposit_id' => $deposit->id,
                'seller_id' => $seller->id,
                'amount' => $request->amount,
                'reference' => $deposit->reference
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de depósito creada. Referencia: ' . $deposit->reference,
                'deposit' => $deposit
            ]);

        } catch (\Exception $e) {
            \Log::error('Error creando solicitud de depósito: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Obtener datos de una cuenta específica para edición
     */
    public function edit($id)
    {
        $seller = Auth::guard('seller')->user();
        $account = $seller->paymentAccounts()->findOrFail($id);

        // Desencriptar datos sensibles para mostrar en el formulario
        $accountData = [
            'id' => $account->id,
            'account_type' => $account->account_type,
            'account_holder_name' => $account->account_holder_name,
            'status' => $account->status
        ];

        // Agregar campos específicos según el tipo
        switch ($account->account_type) {
            case 'debit_card':
                $accountData['card_last_four'] = $account->card_last_four;
                break;
            case 'bank_account':
                $accountData['bank_name'] = $account->bank_name;
                $accountData['account_number'] = $account->account_number ? decrypt($account->account_number) : '';
                break;
            case 'paypal':
                $accountData['paypal_email'] = $account->paypal_email ? decrypt($account->paypal_email) : '';
                break;
        }

        return response()->json([
            'success' => true,
            'account' => $accountData
        ]);
    }

    /**
     * Actualizar cuenta de pago existente
     */
    public function update(Request $request, $id)
    {
        $seller = Auth::guard('seller')->user();
        $account = $seller->paymentAccounts()->findOrFail($id);

        // Validación base
        $validator = Validator::make($request->all(), [
            'account_holder_name' => 'required|string|max:255',
        ]);

        // Validación específica por tipo (solo si se están actualizando datos sensibles)
        if ($request->account_type === 'debit_card' && $request->filled('card_last_four')) {
            $validator->sometimes(['card_last_four'], 'required|string|size:4', function() {
                return true;
            });
        } elseif ($request->account_type === 'bank_account') {
            if ($request->filled('bank_name')) {
                $validator->sometimes(['bank_name'], 'required|string', function() {
                    return true;
                });
            }
            if ($request->filled('account_number')) {
                $validator->sometimes(['account_number'], 'required|string', function() {
                    return true;
                });
            }
        } elseif ($request->account_type === 'paypal' && $request->filled('paypal_email')) {
            $validator->sometimes(['paypal_email'], 'required|email', function() {
                return true;
            });
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Datos base a actualizar
            $updateData = [
                'account_holder_name' => $request->account_holder_name,
            ];

            // Actualizar campos específicos según el tipo si se proporcionan
            switch ($account->account_type) {
                case 'debit_card':
                    if ($request->filled('card_last_four')) {
                        $updateData['card_last_four'] = $request->card_last_four;
                    }
                    break;
                
                case 'bank_account':
                    if ($request->filled('bank_name')) {
                        $updateData['bank_name'] = $request->bank_name;
                    }
                    if ($request->filled('account_number')) {
                        $updateData['account_number'] = encrypt($request->account_number);
                    }
                    break;
                
                case 'paypal':
                    if ($request->filled('paypal_email')) {
                        $updateData['paypal_email'] = encrypt($request->paypal_email);
                    }
                    break;
            }

            // Si se actualizan datos sensibles, marcar como pendiente de verificación
            $sensitiveFields = ['card_last_four', 'account_number', 'paypal_email', 'bank_name'];
            $hasSensitiveChanges = collect($sensitiveFields)->some(function($field) use ($request) {
                return $request->filled($field);
            });

            if ($hasSensitiveChanges) {
                $updateData['status'] = 'pending';
                $updateData['verified_by'] = null;
                $updateData['verified_at'] = null;
            }

            $account->update($updateData);

            // Log para auditoría
            \Log::info("Cuenta de pago actualizada", [
                'account_id' => $account->id,
                'seller_id' => $seller->id,
                'changes' => array_keys($updateData),
                'requires_verification' => $hasSensitiveChanges
            ]);

            $message = $hasSensitiveChanges 
                ? 'Cuenta actualizada exitosamente. Requiere nueva verificación del administrador.'
                : 'Cuenta actualizada exitosamente.';

            return response()->json([
                'success' => true,
                'message' => $message,
                'account' => $account
            ]);

        } catch (\Exception $e) {
            \Log::error('Error actualizando cuenta de pago: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}