<?php

include './file_downloader.php';
define('WP_USE_THEMES', false);
require_once("../../../../wp-load.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $storeeo_sync_id = $_POST['storeeo_sync_id'];
    $status = $_POST['status'];

    
    $storedUser = unserialize($_SESSION['user']);
    if( $status  === "false"){
    
        $headers = [
            'Content-Type' => 'application/json', 
            'user_token'   => $storedUser['user_token'], 
            'shop_url'     => home_url(),
        ];

        $productInfo = array(
            'product_visibility'=> 0,
        );


        
        $url = "$API/products/$storeeo_sync_id";
        
        $response = wp_remote_request($url, [
            'method'  => 'PUT',
            'body'    => json_encode($productInfo),
            'headers' => $headers,
        ]);
        
        if (is_wp_error($response)) {
            echo 'Request failed: ' . $response->get_error_message();
        } else {
            
            $sync_status = update_post_meta($product_id, 'storeeo_sync', $status);
            echo "failed";
        }

    }else{
        $sync_status = update_post_meta($product_id, 'storeeo_sync', $status);
        update_post_meta($product_id, 'storeeo_sync_id', $storeeo_sync_id);
        echo "success";
    }

} else {
    // Debug information
    echo 'No product change status';
}


