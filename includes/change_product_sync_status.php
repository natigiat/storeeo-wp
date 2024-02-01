<?php
// if ( ! defined( 'ABSPATH' ) ) exit;


include './file_downloader.php';
require_once("../../../../wp-load.php");
require_once("./validate.php");


// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product_id'])) {
    $product_id = isset($_POST['product_id']) ? sanitize_post_data($_POST['product_id']) : null;
    $storeeo_sync_id = isset($_POST['storeeo_sync_id']) ? sanitize_post_data($_POST['storeeo_sync_id']) : null;
    $status = isset($_POST['status']) ? sanitize_post_data($_POST['status']) : null;

   
    if( $status  === "false"){
    
        $headers = [
            'Content-Type' => 'application/json', 
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
            echo 'Request failed: ' . esc_html($response->get_error_message());
        } else {
            $sync_status = update_post_meta($product_id, 'storeeo_sync', $status);
            echo "ok";
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


