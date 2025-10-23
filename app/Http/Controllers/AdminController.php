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



    


    





    
}
