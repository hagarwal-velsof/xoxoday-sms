<?php

return [
    'sms_api_key' => env('SMS_API_KEY', 'A144656a34c705f4edbc1b46376be3e22'),

    'sms_message_otp' => env('SMS_MESSAGE_OTP', 'Your OTP for login is {{1}} for Golfer\'s Shot
Powered by Xoxoday'),

'sms_sender_id' => env('SMS_SENDER', 'XOXODY'),

    'sms_api_url' => env('SMS_API_URL', 'https://global.solutionsinfini.com/api/v4/?api_key='),

];