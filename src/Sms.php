<?php

namespace  Xoxoday\Sms;

use Config;
use Illuminate\Support\Facades\Http;
use  Xoxoday\Sms\Model\SmsTable;

class Sms
{
    // Send SMS using Kaleyra API. This will be moved to Queue in future.
    public function postSmsRequest($country = "91", $mobile, $template = "", $variables = array())
    {

        // replacing all the variables from template to actual values
        foreach($variables as $key => $value){
            $template = str_replace("{{".$key."}}", $value, $template);
        }

        //creating an entry for the request in the sms table
        try {
            $sms = SmsTable::create([
                'message' => $template,
                'response' => '',
                'status' => 0,
                'mobile' => $country.$mobile,
            ]);
            
        } catch (QueryException $ex) {
           return false;
        }

        // Fetch values from ENV/Config File
        $url_sms =  Config('kaleyra.sms_api_url') . Config('kaleyra.sms_api_key');

        // Prepare the data to be sent to Gateway
        $payload = array(
            'method' => "sms",
            'to' => $country . $mobile,
            'sender' => Config('kaleyra.sms_sender_id'),
            'message' =>  $template
        );

        // Send the request to Gateway
        $response = Http::asForm()->post($url_sms, $payload);

        // Return the response
        if ($response->status() == 200) {
            $result = json_decode(json_encode($response->object()), true);

            if (isset($result['status']) && $result['status'] == "OK") {
                return true;
            }
        }

        return false;
    }
}
