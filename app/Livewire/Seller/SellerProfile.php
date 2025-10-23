<?php

namespace App\Livewire\Seller;
use Flasher\Toastr\Prime\ToastrInterface;

use Livewire\Component;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;

class SellerProfile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    public $name, $email, $username, $phone, $address;
    public $current_password,$new_password,$new_password_confirmation;

    protected $queryString = ['tab'=>['keep'=>true]];
    public function render()
    {
        return view('livewire.seller.seller-profile',[
            'seller'=>Seller::findOrFail(auth('seller')->id())
        ]);
    }

    public function selectTab($tab){
        $this->tab = $tab;
    }

    public function mount(){
       $this->tab = request()->tab ? request()->tab : $this->tabname;

       //POPULATE
       $seller = Seller::findOrFail(auth('seller')->id());
       $this->name = $seller->name;
       $this->email = $seller->email;
       $this->username = $seller->username;
       $this->phone = $seller->phone;
       $this->address = $seller->address;
    }


    public function updateSellerPersonalDetails(){
        //Validate the form
        $this->validate([
            'name'=>'required|min:5',
            'username'=>'nullable|min:5|unique:sellers,username,'.auth('seller')->id(),
        ]);
        $seller = Seller::findOrFail(auth('seller')->id());
        $seller->name = $this->name;
        $seller->username = $this->username;
        $seller->address = $this->address;
        $seller->phone = $this->phone;
        $update = $seller->save();

        if( $update ){
            $this->dispatch('updateAdminSellerHeaderInfo');
            toastr()->addSuccess('Tus datos personales se han actualizado correctamente.');
        }else{
            toastr()->error('error','Hubo un error al guardar tus datos. Intenta nuevamente.');
        }
    }

    public function updatePassword(){
        $seller = Seller::findOrFail(auth('seller')->id());

        //Validate the form
        $this->validate([
            'current_password'=>[
                'required',
                function($attribute, $value, $fail) use ($seller){
                    if( !Hash::check($value, $seller->password) ){
                        return $fail(__('La contraseña actual es incorrecta.'));
                    }
                }
            ],
            'new_password'=>'required|min:5|max:45|confirmed'
        ]);

        //Update password
        $update = $seller->update([
            'password'=>Hash::make($this->new_password)
        ]);

        if( $update ){
           
            toastr()->addSuccess('Tu contraseña se actualizó correctamente.');
        }else{
            toastr()->error('error','Hubo un error al guardar tus datos. Intenta nuevamente.');
        }
    }

    public function showToastr($type, $message){
        return $this->dispatch('showToastr',[
            'type'=>$type,
            'message'=>$message
        ]);
    }
}
