<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use  Xoxoday\Sms\Model\XosmsMessage;
use Illuminate\Support\Facades\Http;
use Config;

class SendSmsRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsDetails = $this->details;

         // Fetch values from ENV/Config File
         $url_sms =  Config('xosms.kaleyra_sms_api_url') . Config('xosms.kaleyra_sms_api_key');

         // Prepare the data to be sent to Gateway
         $payload = array(
             'method' => "sms",
             'to' => $smsDetails['country'] . $smsDetails['mobile'],
             'sender' => Config('xosms.kaleyra_sms_sender_id'),
             'message' =>  $smsDetails['template']
         );
 
         // Send the request to Gateway
         $response = Http::asForm()->post($url_sms, $payload);

         $sms_sent_flag = 0;

         $result = array();
 
         // Return the response
         if ($response->status() == 200) {
             $result = json_decode(json_encode($response->object()), true);
 
             if (isset($result['status']) && $result['status'] == "OK") {
                $sms_sent_flag = 1;
             }
         }

         if($sms_sent_flag == 1){
            try {
                $sms_status_update = XosmsMessage::where('id', $smsDetails['sms_id'])->update(['status' => 1, 'response' => json_encode($result)]);
            } catch (QueryException $ex) {
                return false;
            }
         }else{
            try {
                $sms_status_update = XosmsMessage::where('id', $smsDetails['sms_id'])->update(['status' => 2, 'response' => json_encode($result)]);
            } catch (QueryException $ex) {
                return false;
            }
         }
    }
}
