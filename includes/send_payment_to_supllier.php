<?php

include './file_downloader.php';
define('WP_USE_THEMES', false);
require_once("../../../../wp-load.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product'])) {
// Assuming $woocommerce is already initialized and $product_id and $quantity are set
    $product_id = 197;  // Example product ID
    $quantity = 2;     // Example quantity

    $nonce = null;
    $cart_url = 'http://localhost/shop2/cart/?add-to-cart=197&quantity=3';

    // // Set up the request headers
    // $headers = array(
    //     'Content-Type' => 'application/json',
    // );
    
    // // Make the GET request using wp_remote_get
    // $response = wp_remote_get(
    //     $cart_url . "/items",
    //     array(
    //         'headers' => $headers,
    //     )
    // );
    
    // if (is_wp_error($response)) {
    //     // Handle error
    //     echo 'Request failed: ' . $response->get_error_message();
    // } else {
    //     // Successful request
    //     $body = wp_remote_retrieve_body($response);

    //     $nonce = wp_remote_retrieve_header($response, 'nonce');

    // }
    

    // // WooCommerce API request parameters
    // $request_data = array(
    //     'id' => $product_id,
    //     'quantity'   => $quantity,
    // );

    // // Set up the request headers
    // $headers_post = array(
    //     'Content-Type' => 'application/json',
    //     'X-WC-Store-API-Nonce'   => $nonce, 
    // );

    // // Make the remote request using wp_remote_post
    // $response = wp_remote_post(
    //     $cart_url."/add-item",
    //     array(
    //         'body'    => json_encode($request_data),
    //         'headers' => $headers_post,
    //     )
    // );

    // if (is_wp_error($response)) {
    //     // Handle error
    //     echo 'Request failed: ' . $response->get_error_message();
    // } else {
    //     // Successful request
    //     $body = wp_remote_retrieve_body($response);
    //     $data = json_decode($body, true);
    //     var_dump($data);
    //     if (isset($data["items"][0]['id']) && $data["items"][0]['id'] > 0) {
    //         // Construct the cart URL
    //         $cart_url = 'http://localhost/shop2/cart/';
    
    //         // Redirect the user to the cart page
    //         echo $cart_url;
    //         exit; // Make sure to exit after the redirect
    //     } else {
    //         echo 'Failed to add items to the cart.';
    //     }
    // }




    // // Make the GET request using wp_remote_get
    // $response = wp_remote_get(
    //     'http://localhost/shop2/wp-json/wc/v1/cart',
    //     array(
    //         'headers' => $headers,
    //     )
    // );

    // if (is_wp_error($response)) {
    //     // Handle error
    //     echo 'Request failed: ' . $response->get_error_message();
    // } else {
    //     // Successful request
    //     $body = wp_remote_retrieve_body($response);
    //     $data = json_decode($body, true);

    //     // Assuming the cart URL is available in the 'url' field of the response
    //     $cart_url = isset($data['url']) ? $data['url'] : '';

    //     if (!empty($cart_url)) {
    //         // Now $cart_url contains the cart URL
    //         echo 'Cart URL: ' . esc_url($cart_url);
    //     } else {
    //         echo 'Cart URL not found in the response.';
    //     }
    // }
    
} else {
    // Debug information
    echo 'No product data in the $_POST array.';
}


