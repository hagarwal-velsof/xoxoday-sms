<?php 

namespace  Xoxoday\Sms;

use Config;
use Illuminate\Support\Facades\Http;


class Sms
{
    
     /**
     * Send SMS
     *
     * @param String  $mobile       Reciever Mobile Number
     * @param String  $template     Content of the SMS
     * @param String  $variable     To replace variable in template with actual value
     * 
     * @return Boolean
     */
    public function notification($mobile, $template = "", $variable = "")
    {
        $curl = curl_init();
        $url_sms =  Config('app.sms_api_url') . Config('app.sms_api_key');

        $payload = array(
            "method" => "sms",
            "to" => "91" . $mobile,
            "sender" => Config('app.sms_sender_id'),
            "message" => str_replace("{{1}}", $variable, $template)
        );

        $response = Http::asForm()->post($url_sms, $payload);

        // dd($response->object());

        $response = curl_exec($curl);
        $response_array = json_decode($response, true);

        if ($response->status() == 200) {
            $result = json_decode(json_encode($response->object()), true);
            if (isset($result['status']) && $result['status'] == "OK") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}