<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Seller;
use App\Models\Client;
use App\Models\VerificationToken; 
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Controllers\Seller\ProductController;

class SellerController extends Controller
{
    public function ingresar(Request $request){
        $data = [
            'pageTitle'=>'Ingresar'
        ];
        return view('back.pages.tienda.auth.ingresar',$data);
    } //End Method

    public function registrarse(Request $request){
        $data = [
            'pageTitle'=>'Registrarse'
        ];
        return view('back.pages.tienda.auth.registrarse',$data);
    } //End Method

    public function home(Request $request){
        $data = [
            'pageTitle'=>'Página de administrador'
        ];
        return view('back.pages.tienda.home',$data);
    } //End Method

    public function registrartienda(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:sellers',
            'password'=>'min:5|required_with:confirm_password|same:confirm_password',
            'confirm_password'=>'min:5'
        ]);

        $seller = new Seller();
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->password = Hash::make($request->password);
        $saved = $seller->save();

        if( $saved ){
            // Enviar correo de confirmación al nuevo vendedor
            try {
                \Mail::to($seller->email)->send(new \App\Mail\SellerRegistrationConfirmation($seller));
                
                // Log del envío exitoso
                \Log::info('Correo de confirmación enviado a nuevo vendedor', [
                    'seller_id' => $seller->id,
                    'seller_email' => $seller->email,
                    'seller_name' => $seller->name
                ]);
            } catch (\Exception $e) {
                // Log del error pero no fallar el registro
                \Log::error('Error enviando correo de confirmación a vendedor', [
                    'seller_id' => $seller->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Enviar notificación al administrador
            try {
                $adminEmail = config('mail.admin_email', 'admin@agroplace.com');
                \Mail::to($adminEmail)->send(new \App\Mail\NewSellerRegistrationNotification($seller));
                
                // Log del envío exitoso
                \Log::info('Notificación de nuevo registro enviada al administrador', [
                    'seller_id' => $seller->id,
                    'admin_email' => $adminEmail
                ]);
            } catch (\Exception $e) {
                // Log del error pero no fallar el registro
                \Log::error('Error enviando notificación al administrador', [
                    'seller_id' => $seller->id,
                    'error' => $e->getMessage()
                ]);
            }

            return redirect()->route('tienda.registro-realizado')->with('success','Registro realizado exitosamente. Revisa tu correo electrónico para más información.');
        } else {
            return redirect()->route('tienda.registrar')->with('fail','Hubo un error al registrarte.');
        }
    }

    public function registroRealizado(Request $request){
        return view('back.pages.tienda.registro-realizado');
    }

    public function loginHandler(Request $request){
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if( $fieldType == 'email' ){
            $request->validate([
                'login_id'=>'required|email|exists:sellers,email',
                'password'=>'required|min:5|max:45'
            ],[
                'login_id.required'=>'Nombre de usuario o correo electrónico es requerido.',
                'login_id.email'=>'Correo inválido.',
                'login_id.exists'=>'El correo no existe en el sistema.',
                'password.required'=>'La contraseña es requerida.'
            ]);
        }else{
            $request->validate([
                'login_id'=>'required|exists:sellers,username',
                'password'=>'required|min:5|max:45'
            ],[
                'login_id.required'=>'Nombre de usuario o correo electrónico es requerido.',
                'login_id.exists'=>'El usuario no existe en el sistema.',
                'password.required'=>'La contraseña es requerida.'
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password
        );

        if( Auth::guard('seller')->attempt($creds) ){
            // return redirect()->route('seller.home');
            if( !auth('seller')->user()->verified ){
                Auth::guard('seller')->logout();
                return redirect()->route('tienda.ingresar')->with('fail','Tu cuenta no ha sido verificada. Una vez haya sido verificada, tendrás acceso como tienda.');
            }else{
                return redirect()->route('tienda.home');
            }
        }else{
            return redirect()->route('tienda.ingresar')->withInput()->with('fail','Contraseña incorrecta.');
        }

        
    } //End Method

    public function logoutHandler(Request $request){
        Auth::guard('seller')->logout();
        return redirect()->route('tienda.ingresar')->with('fail','Se ha cerrado tu sesión.');
    } //End M

    public function profileView(Request $request){
        $data = [
            'pageTitle'=>'Perfil'
        ];
        return view('back.pages.tienda.perfil',$data);
    }

    public function cambiarImagenPerfil(Request $request){
        $seller = Seller::findOrFail(auth('seller')->id());
        $path = 'images/users/sellers/';
        $file = $request->file('sellerProfilePictureFile');
        $old_picture = $seller->getAttributes()['picture'];
        $filename = 'SELLER_IMG_'.$seller->id.'.jpg';

        $upload = Kropify::getFile($file,$filename)->maxWoH(325)->save($path);
        $infos = $upload->getInfo();

        if( $upload ){
            if( $old_picture != null && File::exists(public_path($path.$old_picture)) ){
                File::delete(public_path($path.$old_picture));
            }
            $seller->update(['picture'=>$infos->getName]);

            return response()->json(['status'=>1,'msg'=>'Tu foto de perfil se ha actualizado correctamente.']);
        }else{
            return response()->json(['status'=>0,'msg'=>'Hubo un error.']);
        }
    }
    public function shopSettings(Request $request){
        $seller = Seller::findOrFail(auth('seller')->id());
        $shop = Shop::where('seller_id',$seller->id)->first();
        $shopInfo = '';

        if( !$shop ){
            //Create shop for this seller when not exists
            Shop::create(['seller_id'=>$seller->id]);
            $nshop = Shop::where('seller_id',$seller->id)->first();
            $shopInfo = $nshop;
        }else{
            $shopInfo = $shop;
        }

        $data = [
            'pageTitle'=>'Configuraciones de la tienda',
            'shopInfo'=>$shopInfo
        ];

        return view('back.pages.tienda.configuraciones-tienda',$data);
    }


   


    public function shopSetup(Request $request){
        $seller = Seller::findOrFail(auth('seller')->id());
        $shop = Shop::where('seller_id',$seller->id)->first();
        $old_logo_name = $shop->shop_logo;
        $logo_name = '';
        $path = 'images/shop/';
        $old_banner_name = $shop->shop_banner;
        $banner_name = '';

        //Validate the form
        $request->validate([
            'shop_name' => 'required|unique:shops,shop_name,'.$shop->id,
            'shop_phone' => 'required|numeric',
            'shop_address' => 'required',
            'shop_description' => 'required',
            'shop_logo' => 'nullable|mimes:jpeg,png,jpg',
            'shop_banner' => 'nullable|mimes:jpeg,png,jpg'
        ]);
        
    
        
        if( $request->hasFile('shop_banner') ){
            $file = $request->file('shop_banner');
            $filename = 'SHOPBANNER_'.$seller->id.uniqid().'.'.$file->getClientOriginalExtension();
            $upload = $file->move(public_path($path), $filename);
        
            if( $upload ){
                $banner_name = $filename;
        
                if( $old_banner_name != null && File::exists(public_path($path.$old_banner_name)) ){
                    File::delete(public_path($path.$old_banner_name));
                }
            }
        }
        
        $data = [
            'shop_name' => $request->shop_name,
            'shop_phone' => $request->shop_phone,
            'shop_address' => $request->shop_address,
            'shop_description' => $request->shop_description,
            'shop_logo' => $logo_name != null ? $logo_name : $old_logo_name,
            'shop_banner' => $banner_name != null ? $banner_name : $old_banner_name
        ];
      

        $update = $shop->update($data);

        if( $update ){
            return redirect()->route('tienda.configuraciones-tienda')->with('success','La información de tu tienda se ha actualizado.');
        }else{
            return redirect()->route('tienda.configuraciones-tienda')->with('fail','Hubo un error. Intenta nuevamente.');
        }
       
    }

    
    // Método para mostrar el perfil de un vendedor
    public function show($slug)
    {
        $seller = Seller::where('slug', $slug)->firstOrFail();
        return view('tienda.seller_profile', compact('seller'));
    }

    public function changeProfilePicture(Request $request){
        $seller = Seller::findOrFail(auth('seller')->id());
        $path = 'images/users/sellers/';
        $file = $request->file('sellerProfilePictureFile');
        $old_picture = $seller->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $filename = 'SELLER_IMG_'.rand(2,1000).$seller->id.time().uniqid().'.jpg';

        $upload=$file->move(public_path($path),$filename);

        if($upload){
            if($old_picture != null && File::exists(public_path($path.$old_picture))){
                File::delete(public_path($path.$old_picture));
            }
            $seller->update(['picture'=>$filename]);
            return response()->json(['status'=>1,'msg'=>'Tu foto de perfil se ha actualizado correctamente.']);

        }else{
            return response()->json(['status'=>0,'msg'=>'Hubo un error.']);
        }

    }

    public function index()
    {
        $vendedores = Seller::select('id', 'username', 'name', 'picture')
            ->latest()
            ->get();

        return view('front.sellers.index', compact('vendedores'));
    }

    /**
     * Mostrar todas las ventas del vendedor
     */
    public function misVentas()
    {
        $seller = auth()->guard('seller')->user();
        
        // Obtener order items de productos vendidos por este vendedor
        $ventas = OrderItem::with(['order.client', 'product'])
            ->whereHas('product', function($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->with(['order' => function($query) {
                $query->withCount('items');
            }])
            ->latest()
            ->get()
            ->groupBy('order_id') // Agrupar por order_id para mostrar órdenes completas
            ->map(function($items, $orderId) {
                $firstItem = $items->first();
                $order = $firstItem->order;
                $totalItems = $items->count();
                $totalAmount = $items->sum(function($item) {
                    return $item->price * $item->quantity;
                });
                
                return (object)[
                    'id' => $orderId,
                    'order' => $order,
                    'items_count' => $totalItems,
                    'total_seller_amount' => $totalAmount,
                    'total' => $order->total, // Total completo de la orden
                    'platform_fee' => $order->platform_fee, // Comisión de la plataforma
                    'seller_amount' => $order->seller_amount, // Monto que recibe el vendedor
                    'created_at' => $order->created_at,
                    'status' => $order->status,
                    'client_name' => $order->client->name ?? 'Cliente no disponible',
                    'items' => $items
                ];
            })
            ->values(); // Resetear keys

        return view('back.pages.tienda.ventas.ventas', compact('ventas'));
    }

    /**
     * Mostrar detalle de una venta específica
     */
    public function detalleVenta($orderId)
    {
        $seller = auth()->guard('seller')->user();
        
        // Obtener la orden con los items vendidos por este vendedor
        $order = Order::with(['client', 'items' => function($query) use ($seller) {
            $query->whereHas('product', function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->with('product');
        }])->findOrFail($orderId);

        // Verificar que el vendedor tenga productos en esta orden
        if ($order->items->isEmpty()) {
            abort(403, 'No tienes productos en esta orden');
        }

        return view('back.pages.tienda.ventas.detalle-venta', compact('order'));
    }



  


 

        /**
     * Mostrar todas las compras realizadas por el vendedor
     */
    public function misCompras()
    {
        $seller = Auth::guard('seller')->user();
        
        // Las "compras" del vendedor son órdenes donde existe un client con el mismo email
        // Buscar si existe un cliente con el mismo email del vendedor
        $client = Client::where('email', $seller->email)->first();
        
        if (!$client) {
            // Si no existe cliente con el mismo email, no tiene compras
            return view('back.pages.tienda.compras.mis-compras', [
                'compras' => collect([]), 
                'sinRegistro' => true
            ]);
        }
        
        // Buscar órdenes del cliente asociado
        $compras = Order::where('orders.client_id', $client->id)
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('sellers', 'products.seller_id', '=', 'sellers.id')
            ->select(
                'orders.id',
                'orders.created_at',
                'orders.status',
                'orders.total',
                'orders.shipping_address',
                'orders.shipping_phone',
                'orders.platform_fee',
                'orders.seller_amount',
                DB::raw('COUNT(DISTINCT order_items.id) as items_count'),
                DB::raw('GROUP_CONCAT(DISTINCT sellers.name) as vendedores'),
                DB::raw('GROUP_CONCAT(DISTINCT sellers.id) as seller_ids')
            )
            ->groupBy(
                'orders.id',
                'orders.created_at',
                'orders.status',
                'orders.total',
                'orders.shipping_address',
                'orders.shipping_phone',
                'orders.platform_fee',
                'orders.seller_amount'
            )
            ->orderBy('orders.created_at', 'desc')
            ->paginate(10);
        
        return view('back.pages.tienda.compras.mis-compras', [
            'compras' => $compras,
            'sinRegistro' => false
        ]);
    }

    /**
     * Mostrar detalle de una compra específica del vendedor
     */
    public function detalleCompra($orderId)
    {
        $seller = Auth::guard('seller')->user();
        
        // Buscar si existe un cliente con el mismo email del vendedor
        $client = Client::where('email', $seller->email)->first();
        
        if (!$client) {
            return redirect()->route('tienda.compras')->with('error', 'No tienes compras registradas');
        }
        
        // Verificar que la orden pertenece al cliente asociado al vendedor
        $order = Order::where('orders.id', $orderId)
            ->where('orders.client_id', $client->id)
            ->first();
        
        if (!$order) {
            return redirect()->route('tienda.compras')->with('error', 'Compra no encontrada');
        }
        
        // Obtener los items de la orden con información del vendedor y producto
        $orderItems = OrderItem::where('order_items.order_id', $orderId)
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('sellers', 'products.seller_id', '=', 'sellers.id')
            ->select(
                'order_items.*',
                'products.name as product_name',
                'products.product_image as product_image',
                'sellers.name as seller_name',
                'sellers.email as seller_email',
                'sellers.phone as seller_phone'
            )
            ->get();
        
        // Calcular totales por vendedor
        $totalesPorVendedor = $orderItems->groupBy('seller_name')->map(function ($items) {
            $firstItem = $items->first();
            return [
                'vendedor' => $firstItem->seller_name,
                'email' => $firstItem->seller_email,
                'phone' => $firstItem->seller_phone,
                'productos' => $items->count(),
                'total' => $items->sum(function($item) {
                    return $item->price * $item->quantity;
                })
            ];
        })->values();
        
        return view('back.pages.tienda.compras.detalle-compra', compact('order', 'orderItems', 'totalesPorVendedor'));
    }


}
