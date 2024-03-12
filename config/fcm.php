<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAAs6L9978:APA91bFvFMVWJPP6SKIWUrJB0vClto1ejuTXMnNG0kIq8QP9VYdlxtdI8UKBg2T8OPA4zKl15uNjwYCdBIGIbtuGRrgbkleVcwAk6XtlF3IsPhKCYk_MYJhw0fr4rxI2uopo9tgIaXUg'),
        'sender_id' => env('FCM_SENDER_ID', '771533699007'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'server_topic_url' => 'https://iid.googleapis.com/iid/v1/',
        'timeout' => 30.0, // in second
    ],
];
