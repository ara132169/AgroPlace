<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use constGuards;
use constDefaults;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\GeneralSetting;
use App\Models\Seller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Client;
use App\Notifications\VendedorAprobado;
use App\Notifications\NuevoVendedorNotificado;
use Illuminate\Support\Facades\Notification;



class AdminController extends Controller
{

    public function loginHandler(Request $request){
        $fieldType= filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if($fieldType == 'email'){
            $request->validate([
                'login_id' => 'required|email|exists:admins,email',
                'password'=>'required|min:5|max:45'

            ],[
                'login_id.required' => 'Email o Nombre de usuario es requerido',
                'login_id.email' => 'Email inválido',
                'login_id.exists' => 'El email no existe en el sistema',
                'password.required'=>'La contraseña es requerida'

            ]);
            
        }else{
            $request->validate([
                'login_id' => 'required|exists:admins,username',
                'password'=> 'required|min:5|max:45'
            ],[
                'login_id.required' => 'Email o Nombre de usuario es requerido',
                'login_id.exists' => 'El usuario no existe en el sistema',
                'password.required'=>'La contraseña es requerida'
            ]);
        }

        $creds = array(
            $fieldType => $request->login_id,
            'password'=>$request->password
        );

        if(Auth::guard('admin')->attempt($creds)){
            return redirect()->route('admin.home');
        }else{
            session()->flash('fail','Credenciales incorrectas');
            return redirect()->route('admin.login');

        }  
    }

    public function logoutHandler(Request $request){
        Auth::guard('admin')->logout();
        session()->flash('fail','Se ha cerrado tu sesión.');
        return redirect()->route('admin.login');
    }

    public function sendPasswordResetLink(Request $request){
        $request->validate([
            'email'=>'required|email|exists:admins,email'
        ],[
            'email.required'=>'El :attribute es requerido',
            'email.email' => 'Correo electrónico no válido',
            'email.exists'=>'El :attribute no existe en el sistema'

        ]);

         //obtener datos de administrador

         $admin = Admin::where('email',$request->email)->first();

         //generar token
 
         $token=base64_encode(\Str::random(64));
 
         //verificar si existe un token
 
         $oldToken = DB::table('password_reset_tokens')
         ->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])
         ->first();
         if($oldToken){
             DB::table('password_reset_tokens')
             ->where(['email'=>$request->email,'guard'=>constGuards::ADMIN])
             ->update([
                 'token'=>$token,
                 'created_at'=>\Illuminate\Support\Carbon::now()
             ]);
         }else{
             DB::table('password_reset_tokens')->insert([
                 'email'=>$request->email,
                 'guard'=>constGuards::ADMIN,
                 'token'=>$token,
                 'created_at'=>\Illuminate\Support\Carbon::now()
             ]);
         }
 
         $actionLink = route('admin.reset-password',['token'=>$token,'email'=>$request->email]);
 
         $data = array(
             'actionLink'=>$actionLink,
             'admin'=>$admin
         );
 
         $mail_body=view('email-templates.admin-forgot-email-template',$data)->render();
 
         $mailConfig = array (
             'mail_from_email'=>env('EMAIL_FROM_ADDRESS'),
             'mail_from_name'=>env('EMAIL_FROM_NAME'),
             'mail_recipient_email'=>$admin->email,
             'mail_recipient_name'=>$admin->name,
             'mail_subject'=>'Reestablecer contraseña',
             'mail_body'=>$mail_body
         );
 
         if(sendEmail($mailConfig)){
             session()->flash('success','Te hemos enviado un correo para reestablecer tu contraseña.');
             return redirect()->route('admin.contrasena-olvidada');
         }else{
             session()->flash('fail','Hubo un error, intenta nuevamente.');
             return redirect()->route('admin.contrasena-olvidada');
         }

       

    }


    public function resetPassword(Request $request, $token = null){
        $check_token = DB::table('password_reset_tokens')
        ->where(['token'=>$token,'guard'=>constGuards::ADMIN])
        ->first();
    
    if($check_token){
        $diffMins = \Illuminate\Support\Carbon::createFromFormat('Y-M-D h:i:s',$check_token->created_at)->diffMinutes(
            \Illuminate\Support\Carbon::now()
        );

        if($diffMins > constDefaults::tokenExpiredMinutes){
            session()->flash('fail','El token ha expirado, intentalo nuevamente');
            return redirect()->route('admin.contrasena-olvidada' ,['token'=>$token]);
        }else{
            return view('back.pages.admin.auth.reset-password')->with(['token'=>$token]);
        }

    }else{
        session()->flash('fail','Token invalido, intentalo nuevamente.');
        return redirect()->route('admin.contrasena-olvidada',['token'=>$token]);
    }
    }

    public function resetPasswordHandler(Request $request){
        $request->validate([
            'new_password'=>'required|min:5|max:45|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation'=>'required'
        ]);

        $token = DB::table('password_reset_tokens')
                   ->where(['token'=>$request->token,'guard'=>constGuards::ADMIN])
                   ->first();

        //Get admin details
        $admin = Admin::where('email',$token->email)->first();

        //Update admin password
        Admin::where('email',$admin->email)->update([
            'password'=>Hash::make($request->new_password)
        ]);

        //Delete token record
        DB::table('password_reset_tokens')->where([
            'email'=>$admin->email,
            'token'=>$request->token,
            'guard'=>constGuards::ADMIN
        ])->delete();

        //Send email to notify admin
        $data = array(
            'admin'=>$admin,
            'new_password'=>$request->new_password
        );

        $mail_body = view('email-templates.admin-reset-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email'=>env('EMAIL_FROM_ADDRESS'),
            'mail_from_name'=>env('EMAIL_FROM_NAME'),
            'mail_recipient_email'=>$admin->email,
            'mail_recipient_name'=>$admin->name,
            'mail_subject'=>'Password changed',
            'mail_body'=>$mail_body
        );

        sendEmail($mailConfig);
        return redirect()->route('admin.login')->with('success','Done!, Your password has been changed. Use new password to login into system.');
    }

    public function profileView(Request $request){
        $admin = null;
        if(Auth::guard('admin')->check()){
            $admin = Admin::findOrFail(auth()->id());
        }
        return view('back.pages.admin.perfil',compact('admin'));
    }


    public function changeProfilePicture(Request $request){
        $admin = Admin::findOrFail(auth('admin')->id());
        $path = 'images/users/admin/';
        $file = $request->file('adminProfilePictureFile');
        $old_picture = $admin->getAttributes()['picture'];
        $file_path = $path.$old_picture;
        $filename = 'ADMIN_IMG_'.rand(2,1000).$admin->id.time().uniqid().'.jpg';

        $upload=$file->move(public_path($path),$filename);

        if($upload){
            if($old_picture != null && File::exists(public_path($path.$old_picture))){
                File::delete(public_path($path.$old_picture));
            }
            $admin->update(['picture'=>$filename]);
            return response()->json(['status'=>1,'msg'=>'Tu foto de perfil se ha actualizado correctamente.']);

        }else{
            return response()->json(['status'=>0,'msg'=>'Hubo un error.']);
        }

    }

    public function changeLogo(Request $request){
        $path = 'images/site/';
        $file = $request->file('site_logo');
        $settings = new GeneralSetting();
        $old_logo = $settings->first()->site_logo;
        $file_path = $path.$old_logo;
        $filename = 'LOGO_'.uniqid().'.'.$file->getClientOriginalExtension();
        /*
        $upload = $file->move(public_path($path),$filename);*/
         $upload = $file->move('/home/agromarketmx/public_html/images/site/', $filename);

        if( $upload ){
            if( $old_logo != null && File::exists(public_path($path.$old_logo)) ){
                File::delete(public_path($path.$old_logo));
            }
            $settings = $settings->first();
            $settings->site_logo = $filename;
            $update = $settings->save();

           return back()->with('success', 'logo  actualizado correctamente. ' );
        }else{
            return back()->with('error', 'Hubo un error, intenta nuevamente. ' );
        }
    }

    public function changeFavicon(Request $request){
        $path = 'images/site/';
        $file = $request->file('site_favicon');
        $settings = new GeneralSetting();
        $old_favicon = $settings->first()->site_favicon;
        $filename = 'FAV_'.uniqid().'.'.$file->getClientOriginalExtension();

        $upload = $file->move('/home/agromarketmx/public_html/images/site/', $filename);

        if( $upload ){
           if( $old_favicon != null && File::exists(public_path($path.$old_favicon)) ){
             File::delete(public_path($path.$old_favicon));
           }
           $settings = $settings->first();
           $settings->site_favicon = $filename;
           $update = $settings->save();

        return back()->with('success', 'Favicon  actualizado correctamente. ' );
        }else{
            return back()->with('error', 'Hubo un error, intenta nuevamente. ' );
        }
    }

    public function aprobarVendedor($id)
    {
         // Buscar la solicitud de vendedor por ID
    $solicitud = SolicitudVendedor::findOrFail($id);

    // Cambiar el estado de 'verified' a 1 (verificado)
    $solicitud->verified = 1; // Cambiar a 1 para indicar que está verificado
    $solicitud->status = 'Active'; // Cambiar el estatus si es necesario
    $solicitud->save(); // Guardar los cambios en la base de datos

    // Redirigir al administrador con un mensaje de éxito

        return redirect()->route('admin.home')->with('success', 'Vendedor aprobado correctamente.');
    }

    public function rechazarVendedor($id)
    {
        $vendedor = Seller::findOrFail($id);
        $vendedor->delete();

        return redirect()->route('admin.home')->with('error', 'Solicitud eliminada correctamente.');
    }

    
    public function home()
    {
        // Obtener solicitudes verificadas (status = 'pending' y verified = 1)
        $solicitudesVerificadas = Seller::where('verified', 1)->get();

        // Obtener solicitudes no verificadas (status = 'pending' y verified = 0)
        $solicitudesNoVerificadas = Seller::where('verified', 0)->get();

        return view('back.pages.admin.home', compact('solicitudesVerificadas', 'solicitudesNoVerificadas'));
    }

    public function storeVendedor(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers,email',
            'telefono' => 'required|string|max:15',
            // Otros campos...
        ]);

        // Crear el nuevo vendedor
        $vendedor = Seller::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            // Otros campos...
        ]);

        // Enviar la notificación al administrador
        Notification::send($vendedor, new NuevoVendedorNotificado($vendedor));

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.home')->with('success', 'Vendedor registrado y notificado exitosamente.');
    }

    public function changeBanner(Request $request)
{
    $path = 'images/banners/';
    $settings = GeneralSetting::first();

    // Lista de campos de banners
    $banners = [
        'site_bannero',
        'site_bannert',
        'site_bannerth',
        'site_bannerf',
        'site_bannerfiv',
    ];

    $updatedFields = [];

    foreach ($banners as $bannerField) {
        if ($request->hasFile($bannerField)) {
            $file = $request->file($bannerField);
            $old_file = $settings->$bannerField;

            $filename = strtoupper($bannerField) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $upload = $file->move('/home/agromarketmx/public_html/images/banners/', $filename);

            if ($upload) {
                // Eliminar archivo anterior si existe
                if ($old_file && File::exists(public_path($path . $old_file))) {
                    File::delete(public_path($path . $old_file));
                }

                $settings->$bannerField = $filename;
                $updatedFields[] = $bannerField;
            }
        }
    }

    $settings->save();

    if (count($updatedFields) > 0) {
        return back()->with('success', 'Banner  actualizado correctamente: ' );
    } else {
        return back()->with('error', 'No se subió ningún banner.');
    }
}



    public function todasLasVentas(Request $request)
    {
        // Construir la consulta base
        $query = DB::table('orders')
            ->leftJoin('clients', function($join) {
                $join->on('orders.client_id', '=', 'clients.id')
                     ->where('orders.buyer_type', '=', 'client');
            })
            ->leftJoin('sellers as buyer_sellers', function($join) {
                $join->on('orders.seller_id', '=', 'buyer_sellers.id')
                     ->where('orders.buyer_type', '=', 'seller');
            })
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('sellers', 'products.seller_id', '=', 'sellers.id');

        // Aplicar filtros de búsqueda
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('orders.id', 'like', $search)
                  ->orWhere('clients.name', 'like', $search)
                  ->orWhere('clients.email', 'like', $search)
                  ->orWhere('buyer_sellers.name', 'like', $search)
                  ->orWhere('buyer_sellers.email', 'like', $search)
                  ->orWhere('sellers.name', 'like', $search);
            });
        }

        // Filtro por número de orden específico
        if ($request->filled('order_id')) {
            $query->where('orders.id', $request->order_id);
        }

        // Filtro por vendedor
        if ($request->filled('seller_filter')) {
            $query->where('sellers.name', 'like', '%' . $request->seller_filter . '%');
        }

        // Filtro por comprador
        if ($request->filled('buyer_filter')) {
            $query->where(function($q) use ($request) {
                $buyer = '%' . $request->buyer_filter . '%';
                $q->where('clients.name', 'like', $buyer)
                  ->orWhere('buyer_sellers.name', 'like', $buyer);
            });
        }

        // Filtro por tipo de comprador
        if ($request->filled('buyer_type')) {
            $query->where('orders.buyer_type', $request->buyer_type);
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('orders.status', $request->status);
        }

        // Filtro por rango de fechas
        if ($request->filled('date_from')) {
            $query->whereDate('orders.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('orders.created_at', '<=', $request->date_to);
        }

        // Obtener todas las órdenes con filtros aplicados
        $ventas = $query->select(
                'orders.id',
                'orders.created_at',
                'orders.status',
                'orders.total',
                'orders.buyer_type',
                'orders.shipping_address',
                'orders.shipping_phone',
                DB::raw('CASE 
                    WHEN orders.buyer_type = "client" THEN clients.name 
                    WHEN orders.buyer_type = "seller" THEN buyer_sellers.name 
                    ELSE "Comprador desconocido" 
                END as client_name'),
                DB::raw('CASE 
                    WHEN orders.buyer_type = "client" THEN clients.email 
                    WHEN orders.buyer_type = "seller" THEN buyer_sellers.email 
                    ELSE "No especificado" 
                END as client_email'),
                DB::raw('CASE 
                    WHEN orders.buyer_type = "client" THEN clients.phone 
                    WHEN orders.buyer_type = "seller" THEN buyer_sellers.phone 
                    ELSE NULL 
                END as client_phone'),
                DB::raw('COUNT(order_items.id) as items_count'),
                DB::raw('GROUP_CONCAT(DISTINCT sellers.name) as vendedores'),
                DB::raw('GROUP_CONCAT(DISTINCT sellers.id) as seller_ids')
            )
            ->groupBy(
                'orders.id',
                'orders.created_at',
                'orders.status',
                'orders.total',
                'orders.buyer_type',
                'orders.shipping_address',
                'orders.shipping_phone',
                'clients.name',
                'clients.email',
                'clients.phone',
                'buyer_sellers.name',
                'buyer_sellers.email',
                'buyer_sellers.phone'
            )
            ->orderBy('orders.created_at', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('back.pages.admin.ventas.todas-ventas', compact('ventas'));
    }

    public function detalleVentaAdmin($orderId)
    {
        // Obtener la orden con todas sus relaciones (clientes y vendedores como compradores)
        $order = DB::table('orders')
            ->leftJoin('clients', function($join) {
                $join->on('orders.client_id', '=', 'clients.id')
                     ->where('orders.buyer_type', '=', 'client');
            })
            ->leftJoin('sellers as buyer_sellers', function($join) {
                $join->on('orders.seller_id', '=', 'buyer_sellers.id')
                     ->where('orders.buyer_type', '=', 'seller');
            })
            ->select(
                'orders.*',
                DB::raw('CASE 
                    WHEN orders.buyer_type = "client" THEN clients.name 
                    WHEN orders.buyer_type = "seller" THEN buyer_sellers.name 
                    ELSE "Comprador desconocido" 
                END as client_name'),
                DB::raw('CASE 
                    WHEN orders.buyer_type = "client" THEN clients.email 
                    WHEN orders.buyer_type = "seller" THEN buyer_sellers.email 
                    ELSE "No especificado" 
                END as client_email'),
                DB::raw('CASE 
                    WHEN orders.buyer_type = "client" THEN clients.phone 
                    WHEN orders.buyer_type = "seller" THEN buyer_sellers.phone 
                    ELSE NULL 
                END as client_phone'),
                DB::raw('CASE 
                    WHEN orders.buyer_type = "client" THEN clients.address 
                    WHEN orders.buyer_type = "seller" THEN buyer_sellers.address 
                    ELSE NULL 
                END as client_address')
            )
            ->where('orders.id', $orderId)
            ->first();

        if (!$order) {
            return redirect()->route('admin.ventas')->with('error', 'Venta no encontrada');
        }

        // Obtener los items de la orden con información del vendedor
        $orderItems = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('sellers', 'products.seller_id', '=', 'sellers.id')
            ->select(
                'order_items.*',
                'products.name as product_name',
                'products.product_image',
                'sellers.name as seller_name',
                'sellers.email as seller_email',
                'sellers.phone as seller_phone'
            )
            ->where('order_items.order_id', $orderId)
            ->get();

        // Calcular totales por vendedor
        $totalesPorVendedor = $orderItems->groupBy('seller_name')->map(function ($items) {
            return [
                'vendedor' => $items->first()->seller_name,
                'email' => $items->first()->seller_email,
                'phone' => $items->first()->seller_phone,
                'productos' => $items->count(),
                'total' => $items->sum(function ($item) {
                    return $item->price * $item->quantity;
                })
            ];
        });

        return view('back.pages.admin.ventas.detalle-venta', compact('order', 'orderItems', 'totalesPorVendedor'));
    }


    





    
}
