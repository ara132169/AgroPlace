<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ManualDeposit;
use App\Models\SellerPaymentAccount;
use App\Models\Seller;

class DepositManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Panel principal de gestión de depósitos
     */
    public function index(Request $request)
    {
        $query = ManualDeposit::with(['seller', 'paymentAccount', 'order', 'seller.paymentAccounts'])
                              ->orderBy('requested_at', 'desc');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('seller')) {
            $query->whereHas('seller', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->seller . '%')
                  ->orWhere('email', 'like', '%' . $request->seller . '%');
            });
        }

        $deposits = $query->paginate(20);

        // Estadísticas
        $stats = [
            'pending_amount' => ManualDeposit::where('status', 'pending')->sum('amount'),
            'pending_count' => ManualDeposit::where('status', 'pending')->count(),
            'processing_count' => ManualDeposit::where('status', 'processing')->count(),
            'completed_today' => ManualDeposit::where('status', 'completed')
                                            ->whereDate('completed_at', today())
                                            ->sum('amount'),
            'total_sellers' => SellerPaymentAccount::verified()->distinct('seller_id')->count('seller_id')
        ];

        return view('back.pages.admin.deposits.manage-deposits', compact('deposits', 'stats'));
    }

    /**
     * Obtener detalles del depósito y cuentas disponibles del vendedor
     */
    public function getDepositDetails($id)
    {
        \Log::info('getDepositDetails called for ID: ' . $id);
        
        try {
            $deposit = ManualDeposit::with(['seller', 'paymentAccount'])->findOrFail($id);
            \Log::info('Deposit found: ' . $deposit->id);
            
            // Obtener todas las cuentas verificadas del vendedor
            $sellerAccounts = SellerPaymentAccount::where('seller_id', $deposit->seller_id)
                                                ->orderBy('is_verified', 'desc')
                                                ->orderBy('created_at', 'desc')
                                                ->get()
                                                ->map(function($account) {
                                                    return [
                                                        'id' => $account->id,
                                                        'display_info' => $account->display_info,
                                                        'account_holder_name' => $account->account_holder_name,
                                                        'account_type' => $account->account_type,
                                                        'is_verified' => $account->is_verified,
                                                        'is_active' => $account->is_active,
                                                        'admin_full_info' => $account->admin_full_info
                                                    ];
                                                });

            \Log::info('Seller accounts found: ' . $sellerAccounts->count());

            return response()->json([
                'success' => true,
                'deposit' => [
                    'id' => $deposit->id,
                    'amount' => number_format($deposit->amount, 2),
                    'reference' => $deposit->reference,
                    'description' => $deposit->description,
                    'payment_account_id' => $deposit->payment_account_id,
                    'seller' => [
                        'id' => $deposit->seller->id,
                        'name' => $deposit->seller->name,
                        'email' => $deposit->seller->email,
                    ],
                    'payment_account' => [
                        'id' => $deposit->paymentAccount->id,
                        'display_info' => $deposit->paymentAccount->display_info,
                        'account_holder_name' => $deposit->paymentAccount->account_holder_name
                    ]
                ],
                'seller_accounts' => $sellerAccounts
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting deposit details: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error al obtener detalles: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get account details for admin use
     */
    public function getAccountDetails(Request $request)
    {
        try {
            $accountId = $request->input('account_id');
            
            if (!$accountId) {
                return response()->json([
                    'success' => false, 
                    'message' => 'ID de cuenta requerido'
                ], 400);
            }

            $account = SellerPaymentAccount::findOrFail($accountId);

            return response()->json([
                'success' => true,
                'admin_full_info' => $account->admin_full_info,
                'account' => [
                    'id' => $account->id,
                    'display_info' => $account->display_info,
                    'account_holder_name' => $account->account_holder_name,
                    'account_type' => $account->account_type,
                    'is_verified' => $account->is_verified,
                    'is_active' => $account->is_active,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting account details: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al obtener detalles de cuenta'], 500);
        }
    }

    /**
     * Actualizar estado de depósito
     */
    public function updateStatus(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $deposit = ManualDeposit::with(['seller', 'paymentAccount'])->findOrFail($id);

        // LOG DETALLADO DE LA PETICIÓN
        \Log::info("INICIO actualización de depósito", [
            'deposit_id' => $id,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'current_status' => $deposit->status,
            'requested_status' => $request->status,
            'request_data' => $request->all(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        $request->validate([
            'status' => 'required|in:processing,completed,failed',
            'destination_account_id' => 'nullable|exists:seller_payment_accounts,id',
            'deposit_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'admin_notes' => 'nullable|string',
            'failure_reason' => 'nullable|string'
        ]);

        try {
            // Cambiar cuenta destino si se especifica una diferente
            if ($request->destination_account_id && $request->destination_account_id != $deposit->payment_account_id) {
                $newAccount = SellerPaymentAccount::where('id', $request->destination_account_id)
                                                ->where('seller_id', $deposit->seller_id)
                                                ->where('is_verified', true)
                                                ->first();
                
                if ($newAccount) {
                    $deposit->update(['payment_account_id' => $request->destination_account_id]);
                    
                    \Log::info("Cuenta destino cambiada para depósito", [
                        'deposit_id' => $deposit->id,
                        'admin_id' => $admin->id,
                        'old_account' => $deposit->payment_account_id,
                        'new_account' => $request->destination_account_id
                    ]);
                }
            }
            
            if ($request->status === 'processing') {
                $deposit->markAsProcessing($admin->id);
                
                // Actualizar campos adicionales
                $deposit->update([
                    'deposit_method' => $request->deposit_method,
                    'external_transaction_id' => $request->transaction_id,
                    'admin_notes' => $request->admin_notes
                ]);

                $message = "Depósito marcado como procesando";

            } elseif ($request->status === 'completed') {
                \Log::info("COMPLETANDO depósito", [
                    'deposit_id' => $deposit->id,
                    'admin_id' => $admin->id,
                    'transaction_id' => $request->transaction_id,
                    'deposit_method' => $request->deposit_method,
                    'admin_notes' => $request->admin_notes
                ]);

                $deposit->markAsCompleted(
                    $request->transaction_id,
                    $request->deposit_method,
                    $request->admin_notes
                );

                // Log para auditoría
                \Log::info("DEPÓSITO COMPLETADO por admin", [
                    'deposit_id' => $deposit->id,
                    'admin_id' => $admin->id,
                    'seller_id' => $deposit->seller_id,
                    'amount' => $deposit->amount,
                    'method' => $request->deposit_method,
                    'final_status' => $deposit->fresh()->status
                ]);

                $message = "Depósito completado exitosamente";

            } elseif ($request->status === 'failed') {
                $deposit->markAsFailed(
                    $request->failure_reason ?? 'Error no especificado',
                    $request->admin_notes
                );

                $message = "Depósito marcado como fallido";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'deposit' => $deposit
            ]);

        } catch (\Exception $e) {
            \Log::error('Error actualizando depósito: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Procesar todos los depósitos pendientes automáticamente
     */
    public function processAllPending(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // LOG CRÍTICO - Esta función afecta múltiples depósitos
        \Log::warning("PROCESAMIENTO EN LOTE INICIADO", [
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
            'request_data' => $request->all()
        ]);
        
        try {
            $pendingDeposits = ManualDeposit::where('status', 'pending')->get();
            $processed = 0;

            \Log::info("Depósitos pendientes encontrados", [
                'count' => $pendingDeposits->count(),
                'deposit_ids' => $pendingDeposits->pluck('id')->toArray()
            ]);

            foreach ($pendingDeposits as $deposit) {
                \Log::info("Procesando depósito individual", [
                    'deposit_id' => $deposit->id,
                    'amount' => $deposit->amount,
                    'seller_id' => $deposit->seller_id,
                    'current_status' => $deposit->status
                ]);

                $deposit->markAsProcessing($admin->id);
                $deposit->update([
                    'deposit_method' => 'bulk_processing',
                    'admin_notes' => 'Procesado en lote por admin ' . $admin->name . ' el ' . now()
                ]);
                $processed++;

                \Log::info("Depósito procesado", [
                    'deposit_id' => $deposit->id,
                    'new_status' => $deposit->fresh()->status
                ]);
            }

            \Log::info("Procesamiento en lote de depósitos", [
                'admin_id' => $admin->id,
                'processed_count' => $processed
            ]);

            return response()->json([
                'success' => true,
                'message' => "Se procesaron {$processed} depósitos pendientes"
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en procesamiento masivo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error en procesamiento masivo'
            ], 500);
        }
    }

    /**
     * Verificar cuenta de pago de vendedor
     */
    public function verifyPaymentAccount(Request $request, $accountId)
    {
        $admin = Auth::guard('admin')->user();
        $account = SellerPaymentAccount::with('seller')->findOrFail($accountId);

        $request->validate([
            'verified' => 'required|boolean',
            'notes' => 'nullable|string'
        ]);

        try {
            if ($request->verified) {
                $account->verify($admin->id);
                $message = "Cuenta verificada exitosamente";
            } else {
                $account->update([
                    'is_verified' => false,
                    'verified_at' => null,
                    'verified_by' => null
                ]);
                $message = "Verificación revocada";
            }

            \Log::info("Cuenta de pago verificada", [
                'account_id' => $account->id,
                'seller_id' => $account->seller_id,
                'admin_id' => $admin->id,
                'verified' => $request->verified
            ]);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            \Log::error('Error verificando cuenta: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Panel de cuentas de pago por verificar
     */
    public function pendingAccounts()
    {
        $accounts = SellerPaymentAccount::with('seller')
                                      ->where('is_verified', false)
                                      ->orderBy('created_at', 'desc')
                                      ->paginate(20);

        return view('back.pages.admin.deposits.pending-accounts', compact('accounts'));
    }

    /**
     * Estadísticas detalladas
     */
    public function stats()
    {
        $stats = [
            'deposits' => [
                'total' => ManualDeposit::count(),
                'pending' => ManualDeposit::where('status', 'pending')->count(),
                'processing' => ManualDeposit::where('status', 'processing')->count(),
                'completed' => ManualDeposit::where('status', 'completed')->count(),
                'failed' => ManualDeposit::where('status', 'failed')->count(),
            ],
            'amounts' => [
                'total_requested' => ManualDeposit::sum('amount'),
                'total_completed' => ManualDeposit::where('status', 'completed')->sum('amount'),
                'pending_amount' => ManualDeposit::where('status', 'pending')->sum('amount'),
            ],
            'accounts' => [
                'total' => SellerPaymentAccount::count(),
                'verified' => SellerPaymentAccount::where('is_verified', true)->count(),
                'pending_verification' => SellerPaymentAccount::where('is_verified', false)->count(),
            ],
            'by_method' => ManualDeposit::selectRaw('deposit_method, COUNT(*) as count, SUM(amount) as total')
                                      ->whereNotNull('deposit_method')
                                      ->groupBy('deposit_method')
                                      ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Ver todas las cuentas de vendedores
     */
    public function sellerAccounts(Request $request)
    {
        $query = SellerPaymentAccount::with(['seller', 'verifiedBy'])
                                   ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_verified', false);
            }
        }

        if ($request->filled('type')) {
            $query->where('account_type', $request->type);
        }

        if ($request->filled('seller')) {
            $query->whereHas('seller', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->seller . '%')
                  ->orWhere('email', 'like', '%' . $request->seller . '%');
            });
        }

        $accounts = $query->paginate(20);

        // Estadísticas
        $stats = [
            'total_accounts' => SellerPaymentAccount::count(),
            'verified_accounts' => SellerPaymentAccount::where('is_verified', true)->count(),
            'pending_verification' => SellerPaymentAccount::where('is_verified', false)->count(),
            'total_sellers' => SellerPaymentAccount::distinct('seller_id')->count('seller_id'),
            'by_type' => SellerPaymentAccount::selectRaw('account_type, COUNT(*) as count')
                                           ->groupBy('account_type')
                                           ->pluck('count', 'account_type')
        ];

        return view('back.pages.admin.deposits.seller-accounts', compact('accounts', 'stats'));
    }

    /**
     * Ver detalles de una cuenta específica
     */
    public function viewSellerAccount($id)
    {
        $account = SellerPaymentAccount::with(['seller', 'verifiedBy', 'deposits' => function($query) {
            $query->orderBy('requested_at', 'desc')->limit(10);
        }])->findOrFail($id);

        // Estadísticas de la cuenta
        $accountStats = [
            'total_deposits' => $account->deposits()->count(),
            'total_amount' => $account->deposits()->where('status', 'completed')->sum('amount'),
            'pending_deposits' => $account->deposits()->where('status', 'pending')->count(),
            'last_deposit' => $account->deposits()->latest('requested_at')->first()
        ];

        return view('back.pages.admin.deposits.seller-account-details', compact('account', 'accountStats'));
    }

    /**
     * Verificar cuenta de vendedor
     */
    public function verifySellerAccount(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $account = SellerPaymentAccount::findOrFail($id);

        $request->validate([
            'action' => 'required|in:verify,reject',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            if ($request->action === 'verify') {
                $account->verify($admin->id);
                
                // Log para auditoría
                \Log::info("Cuenta de pago verificada por admin", [
                    'account_id' => $account->id,
                    'admin_id' => $admin->id,
                    'seller_id' => $account->seller_id,
                    'account_type' => $account->account_type
                ]);

                $message = "Cuenta verificada exitosamente";
            } else {
                $account->update([
                    'is_verified' => false,
                    'admin_notes' => $request->admin_notes
                ]);

                $message = "Cuenta rechazada";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'account' => $account->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error("Error verificando cuenta: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error procesando verificación'
            ], 500);
        }
    }

    /**
     * Obtener detalles completos de una cuenta de pago para admin
     */
    public function getAccountFullDetails($accountId)
    {
        try {
            $account = SellerPaymentAccount::with('seller')->findOrFail($accountId);
            
            return response()->json([
                'success' => true,
                'admin_full_info' => $account->admin_full_info,
                'account' => [
                    'id' => $account->id,
                    'seller_name' => $account->seller->name,
                    'account_holder_name' => $account->account_holder_name,
                    'account_type' => $account->account_type,
                    'display_info' => $account->display_info,
                    'is_verified' => $account->is_verified,
                    'is_active' => $account->is_active,
                    'created_at' => $account->created_at->format('d/m/Y H:i'),
                    'updated_at' => $account->updated_at->format('d/m/Y H:i')
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting account full details: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error al obtener detalles de cuenta'
            ], 500);
        }
    }
}