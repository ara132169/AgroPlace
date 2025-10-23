<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\GeneralSetting;
use App\Models\SocialNetwork;
use App\Models\Category;
use App\Models\SubCategory;

/**send email funcion using phpmailer */

if(!function_exists('sendEmail')){
    function sendEmail($mailConfig){
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = env('EMAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('EMAIL_USERNAME');
        $mail->Password= env('EMAIL_PASSWORD');
        $mail->SMTPSECURE = env ('EMAIL_ENCRYPTION');
        $mail->PORT = env('EMAIL_PORT');
        $mail->setFrom($mailConfig['mail_from_email'], $mailConfig['mail_from_name']);
        $mail->addAddress($mailConfig['mail_recipient_email'],$mailConfig['mail_recipient_name']);
        $mail->isHTML(true);
        $mail->Subject = $mailConfig['mail_subject'];
        $mail->Body = $mailConfig['mail_body'];
        if($mail->send()){
            return true;
        }else
        {
            return false;
        }
    }
}

/** GET GENERAL SETTINGS WITH CACHE */
if( !function_exists('get_settings') ){
    function get_settings(){
        return cache()->remember('site_settings', 3600, function () {
            $settings = new GeneralSetting();
            $settings_data = $settings->first();

            if( $settings_data ){
                return $settings_data;
            } else {
                $settings->insert([
                    'site_name'=>'AgroMarketPlace',
                    'site_email'=>'info@agromarketplace.com'
                ]);
                return $settings->first();
            }
        });
    }
}


/** GET SOCIAL NETWORKS */
if( !function_exists('get_social_network') ){
    function get_social_network(){
        $results = null;
        $social_network = new SocialNetwork();
        $social_network_data = $social_network->first();

        if( $social_network_data ){
            $results = $social_network_data;
        }else{
            $social_network->insert([
                'facebook_url'=>null,
                'instagram_url'=>null,
 
            ]);
            $new_social_network_data = $social_network->first();
            $results = $new_social_network_data;
        }
        return $results;
    }
}

//FRONTEND::
/** GET FRONT END CATEGORIES WITH CACHE */
if( !function_exists('get_categories') ){
    function get_categories(){
        return cache()->remember('frontend_categories', 1800, function () {
            $categories = Category::with('subcategories')->orderBy('ordering','asc')->get();
            return !empty($categories) ? $categories : [];
        });
    }
}

/** GET IMAGE URL WITH FALLBACK */
if( !function_exists('get_image') ){
    function get_image($path, $default = 'default.jpg'){
        if( empty($path) ){
            return asset('images/' . $default);
        }
        
        $fullPath = public_path('images/' . $path);
        if( file_exists($fullPath) ){
            return asset('images/' . $path);
        }
        
        return asset('images/' . $default);
    }
}