<?php

namespace App\Http\Controllers;
use Xoxoday\Sms\Sms;

class TestController extends Controller
{
  public function sendSms()
  {
    $country = '91';    //pass your country code
    $mobile = '';     // mobile number
    $template = ''; // kaleyra template text
    $variables = array( // array to replace variable in template file
        '1' => '1234'   //send the otp here
    );
    $sms =new Sms();
    $result = $sms->postSmsRequest($country, $mobile, $template, $variables);
    print_r($result);

    die('__');
  }
}
