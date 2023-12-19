<?php

include './file_downloader.php';
define('WP_USE_THEMES', false);
require_once("../../../../wp-load.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product_store_id'])) {
    $product_store_id = $_POST['product_store_id'];
    // Get the product object
    $product = wc_get_product($product_store_id);

    $logo_id = get_theme_mod('custom_logo');
    $logo_url = wp_get_attachment_image_src($logo_id, 'full');


    // Check if the product exists
    if ($product) {
        // Extract required product information
        $productInfo = array(
            'product_title'         => $product->get_name(),
            'product_content'       => $product->get_description(),
            'product_visibility'    => true, // You may need to customize this based on your criteria
            'product_price'         => (float)$product->get_price(),
            'product_quantity'      => (float)$product->get_stock_quantity() ? $product->get_stock_quantity() : 0,
            'product_sku'           => $product->get_sku(),
            'product_main_image'    => wp_get_attachment_url( $product->get_image_id()),
            'product_store_id'      =>(float)$product_store_id,
            'product_regular_price' => (float)$product->get_regular_price(),
            'product_storeeo_price' => (float)get_post_meta($product_store_id, "_custom_product_storeeo_price", true),
            'logo_url'              => $logo_url['url']
        );

        $storedUser = unserialize($_SESSION['user']);

        // Define additional headers
        $headers = array(
            'Content-Type' => 'application/json', 
            'user_token' => $storedUser['user_token'], 
            'shop_url'=>home_url(),
        );

        $url = $API."/products";

        echo $API;

        $response = wp_remote_post(
            $url,
            array(
                'body'    => json_encode($productInfo),
                'headers' => $headers,
            )
        );

        if (is_wp_error($response)) {
            // Handle error
            echo 'Request failed: ' . $response->get_error_message();
        } else {
            // Successful request
            $body = wp_remote_retrieve_body($response);
            $decoded_body = json_decode($body, true); // If the response is in JSON format
        
            // Now you can work with the response data
            echo 'Response data: ' . print_r($decoded_body, true);
        }

        
        
    } else {
        echo "Product not found";
    }






    
} else {
    // Debug information
    echo 'No product data in the $_POST array.';
}


