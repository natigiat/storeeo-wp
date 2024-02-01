<?php


include './file_downloader.php';
require_once("../../../../wp-load.php");
require_once("./validate.php");


$headers = [
    'Content-Type' => 'application/json', 
    'shop_url'     => home_url(),
];

$url = "$API/auth/shop";

$response = wp_remote_request($url, [
    'method'  => 'GET',
    'headers' => $headers,
]);

if (is_wp_error($response)) {
    echo 'failed';
} else {  

    $body = wp_remote_retrieve_body($response);
    $decoded_body = json_decode($body, true);
    var_dump($decoded_body);
    echo "ok";
}