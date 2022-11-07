<?php

namespace  Xoxoday\Sms;

use Config;
use  Xoxoday\Sms\Model\XosmsMessage;

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
            $sms = XosmsMessage::create([
                'message' => $template,
                'response' => '',
                'status' => 0,
                'mobile' => $country.$mobile,
            ]);
            
        } catch (QueryException $ex) {
           return false;
        }

        if($sms){

            //Creating an entry for the job to send sms
            $details = array(
                'country' =>  $country,
                'mobile' => $mobile,
                'template' => $template,
                'sms_id' => $sms['id'],
                );
            dispatch(new \App\Jobs\SendSmsRequest($details)); 
            return $sms['id'];    
        }

        return false;        
    }

    public function getSmsStatus($sms_id){
        if($sms_id != '' ){
            $status = '';
            try {
                $sms_details = XosmsMessage::where('id', $sms_id)->first();
            } catch (QueryException $ex) {
               return false;
            }

            if($sms_details){
                if($sms_details['status'] == 0){
                    $status = 'Pending';
                }elseif($sms_details['status'] == 1){
                    $status = 'Delivered';
                }else{
                    $status = 'Failed';
                }
                return $status;
            }
        }
        return false;
    }

    public function getOverallDeliveryStatus(){
        $data = XosmsMessage::groupBy('status')->selectRaw('count(*) as total, status')->pluck('total', 'status');
        $status_wise_data = array();
        $total_sms = 0;

        foreach($data as $key => $value){
            $total_sms += $value;
            if($key == 0){
                $status_wise_data[] = array(
                    'status' => 'Pending',
                    'total' => $value
                );
            }else if($key == 1){
                $status_wise_data[] = array(
                    'status' => 'Deilvered',
                    'total' => $value
                );
            }else if($key == 2){
                $status_wise_data[] = array(
                    'status' => 'Failed',
                    'total' => $value
                );
            }
        }
        $complete_data = array(
            'total_sms' => $total_sms,
            'status_wise_data' => $status_wise_data
        );
        return $complete_data;
    }
}
