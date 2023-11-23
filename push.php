<?php

include_once(__DIR__."/push_server_key.php");

$json = '{
"notification":{"title": "ログイン通知","body": "ユーザーID: guchi\nログイン日時: 2023/11/12 23:12:32"},
"to": "cDnPiUwynKd0RckeiWAqX2:APA91bFZVUa6GLAG2s4CK8gmPcYu_gl2DLu2TUIO0crbirUpdDsDoznU93dGAMlM3z22i0S7GkHRy7ao_PD-fIOJBeDdYlraF1uLq3SHBuDWN3xcMzDQwCMZQ74Mmd-NdKZ0F22WnMIB"
}';

$ch = curl_init();

$headers = array(
    'Content-Type: application/json',
    'Authorization: key='.$key
);

curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => $json
));

$response = curl_exec($ch);

curl_close($ch);