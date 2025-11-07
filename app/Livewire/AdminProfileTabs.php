<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminProfileTabs extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab'=>['keep'=>true]];
    public $name, $email, $username, $admin_id;
    public $current_password, $new_password, $new_password_confirmation;
    

    public function selectTab($tab){
        $this->tab = $tab;
    }

    public function mount(){
        $this->tab = request()->tab ? request()->tab : $this->tabname;

        if( Auth::guard('admin')->check() ){
            $admin = Admin::findOrFail(auth('admin')->id());
            $this->admin_id = $admin->id;
            $this->name = $admin->name;
            $this->email = $admin->email;
            $this->username = $admin->username;
        }
    }

    public function updateAdminPersonalDetails(){
        $this->validate([
            'name'=>'required|min:5',
            'email'=>'required|email|unique:admins,email,'.$this->admin_id,
            'username'=>'required|min:3|unique:admins,username,'.$this->admin_id
        ]);

        Admin::find($this->admin_id)
             ->update([
                'name'=>$this->name,
                'email'=>$this->email,
                'username'=>$this->username
             ]);

        $this->dispatch('updateAdminSellerHeaderInfo');
        $this->dispatch('updateAdminInfo',[
            'adminName'=>$this->name,
            'adminEmail'=>$this->email
        ]);
        $this->showToastr('success','Tus datos personales han sido actualizados.');
    }

    public function updatePassword(){
        $this->validate([
            'current_password'=>[
                'required', function($attribute, $value, $fail){
                    $admin = Admin::find($this->admin_id);
                    if(!Hash::check($value, $admin->password)){
                        return $fail('La contraseña actual es incorrecta');
                    }
                }
            ],
            'new_password'=>'required|min:5|max:45|confirmed'
        ],[
            'current_password.required' => 'La contraseña actual es requerida',
            'new_password.required' => 'La nueva contraseña es requerida',
            'new_password.min' => 'La nueva contraseña debe tener al menos 5 caracteres',
            'new_password.max' => 'La nueva contraseña no puede tener más de 45 caracteres',
            'new_password.confirmed' => 'La confirmación de contraseña no coincide'
        ]);

        try {
            // Actualizar la contraseña
            $admin = Admin::findOrFail($this->admin_id);
            $admin->update([
                'password' => Hash::make($this->new_password)
            ]);

            // Limpiar campos inmediatamente
            $this->current_password = null;
            $this->new_password = null;
            $this->new_password_confirmation = null;

            // Intentar enviar notificación por correo (de forma asíncrona para no bloquear)
            try {
                $this->sendPasswordChangeNotification($admin);
                $this->showToastr('success', 'Contraseña actualizada exitosamente. El cambio ha sido registrado en el sistema.');
            } catch (\Exception $emailError) {
                \Log::error('Error al procesar notificación: ' . $emailError->getMessage());
                $this->showToastr('success', 'Contraseña actualizada exitosamente.');
            }
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar contraseña de admin: ' . $e->getMessage());
            $this->showToastr('error', 'Ocurrió un error al actualizar la contraseña. Inténtalo nuevamente.');
        }
    }

    private function sendPasswordChangeNotification($admin){
        try {
            // Por ahora, solo registrar en log en lugar de enviar email
            // para evitar problemas de conexión SMTP
            
            \Log::info('=== CAMBIO DE CONTRASEÑA ADMINISTRADOR ===');
            \Log::info('Usuario: ' . $admin->name);
            \Log::info('Email: ' . $admin->email);
            \Log::info('Fecha: ' . now()->format('d/m/Y H:i:s'));
            \Log::info('IP: ' . request()->ip());
            \Log::info('===========================================');
            
            // Comentamos temporalmente el envío de email hasta resolver SMTP
            /*
            $data = [
                'admin' => $admin,
                'date' => now()->format('d/m/Y H:i:s'),
                'ip' => request()->ip()
            ];

            $mail_body = view('email-templates.admin-password-changed', $data)->render();

            $mailConfig = [
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $admin->email,
                'mail_recipient_name' => $admin->name,
                'mail_subject' => 'Contraseña actualizada - ' . env('APP_NAME'),
                'mail_body' => $mail_body
            ];

            $emailSent = sendEmail($mailConfig);
            
            if ($emailSent) {
                \Log::info('Email de cambio de contraseña enviado exitosamente a: ' . $admin->email);
            } else {
                \Log::warning('No se pudo enviar el email de cambio de contraseña a: ' . $admin->email);
            }
            */
            
        } catch (\Exception $e) {
            \Log::error('Error al procesar notificación de cambio de contraseña: ' . $e->getMessage());
        }
    }

    public function showToastr($type, $message){
         $this->dispatch('showToastr',[
             'type'=>$type,
             'message'=>$message
        ]);
    }
    public function mostrarToast()
    {
        $this->dispatch('mostrarBootstrapToast', [
            'title' => '¡Éxito!',
            'message' => 'Tus datos han sido actualizados.'
        ]); 
    }

    

    
  


    public function render()
    {
        return view('livewire.admin-profile-tabs');
    }
}
