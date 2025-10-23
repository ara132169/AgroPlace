<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GeneralSetting;
use App\Models\SocialNetwork;

class AdminSettings extends Component
{
    public $tab = null;
    public $default_tab = 'general_settings';
    protected $queryString = ['tab'=>['keep'=>true]];
    public $site_name, $site_email, $site_phone, $site_meta_keywords, $site_meta_description, $site_logo, $site_favicon,$site_bannero,$site_bannert,$site_bannerth;
    public $facebook_url, $instagram_url;

    public function selectTab($tab){
        $this->tab = $tab;
    }

    public function mount(){
        $this->tab = request()->tab ? request()->tab : $this->default_tab;

        $this->site_name = get_settings()->site_name;
        $this->site_email = get_settings()->site_email;
        $this->site_phone = get_settings()->site_phone;
        $this->site_meta_keywords = get_settings()->site_meta_keywords;
        $this->site_meta_description = get_settings()->site_meta_description;
        $this->site_logo = get_settings()->site_logo;
        $this->site_favicon = get_settings()->site_favicon;
        $this->site_bannero = get_settings()->site_bannero;
        $this->site_bannert = get_settings()->site_bannert;
        $this->site_bannerth = get_settings()->site_bannerth;

        //redes sociales
        $this->facebook_url = get_social_network()->facebook_url;
        $this->instagram_url = get_social_network()->instagram_url;  
    }

    public function updateGeneralSettings(){
        $this->validate([
            'site_name'=>'required',
            'site_email'=>'required|email'
        ]);

        $settings = new GeneralSetting();
        $settings = $settings->first();
        $settings->site_name = $this->site_name;
        $settings->site_email = $this->site_email;
        $settings->site_phone = $this->site_phone;
        $settings->site_meta_keywords = $this->site_meta_keywords;
        $settings->site_meta_description = $this->site_meta_description;
        $update = $settings->save();

        if( $update ){
            toastr()->addSuccess('Se han aplicado correctamente los cambios.');
        }else{
            toastr()->error('Hubo un error al actualizar los datos.');
        }
    }

    public function updateSocialNetworks(){
        $social_network = new SocialNetwork();
        $social_network = $social_network->first();
        $social_network->facebook_url = $this->facebook_url;
        $social_network->instagram_url = $this->instagram_url;

        $update = $social_network->save();

        if( $update ){
            toastr()->addSuccess('Se ha actualizado la información.');
        }else{
            toastr()->error('Hubo un error. Intenta nuevamente.');
        }
    }

    public function showToastr($type, $message){
        return $this->dispatch('showToastr',[
            'type'=>$type,
            'message'=>$message
        ]);
    }


    public function render()
    {
        return view('livewire.admin-settings');
    }
}
