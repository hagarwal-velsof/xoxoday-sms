**SMS Package**

## What is SMS API?

This package takes care of all SMS related Tasks using different Gateways.

## Installation

Require this package with composer. 

```sh
$ composer require xoxoday/sms
```

## Database table migration

Create xosms_messages table in your database.

```sh
$ php artisan migrate
```

## Publish package

Create config/xosms.php and Jobs/SendSmsRequest.php file using the following artisan command:

```sh
$ php artisan vendor:publish  --tag="sms_files"
```

## Complete configuration

### Set your credentials

Open config/xosms.php configuration file and set your credentials:

```php
return [
    'kaleyra_sms_api_key' => env('KALEYRA_SMS_API_KEY', 'Set your API KEY'),

    'kaleyra_sms_message_otp' => env('KALEYRA_SMS_MESSAGE_OTP', 'Your OTP for login is {{1}} for Golfer\'s Shot
Powered by Xoxoday'),

'kaleyra_sms_sender_id' => env('KALEYRA_SMS_SENDER', 'Set your SMS sender ID'),

    'kaleyra_sms_api_url' => env('KALEYRA_SMS_API_URL', 'https://global.solutionsinfini.com/api/v4/?api_key='),
];
```

Configure the queue in your laravel project to use the SMS functionality.
To configure queue run artisan command.

```php
$ php artisan queue:table
$ php artisan migrate
```

Update queue connection in your environment file

QUEUE_CONNECTION={{database}}

## How to use

Refer code from the sample.php file and execute the functionality of the package