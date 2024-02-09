<?php
// if ( ! defined( 'ABSPATH' ) ) exit;

include './file_downloader.php';
require_once("../../../../wp-load.php");
include_once('./products.php');
require_once("./validate.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product_store_id'])) {
    $product_store_id =  sanitize_post_data($_POST['product_store_id']);
    $storeeo_price =  sanitize_post_data($_POST['storeeo_price']);

    
    // Get the product object
    $product = wc_get_product($product_store_id);

    
    // Check if the product exists
    if ($product) {
        product_to_storeeo_function($product ,$storeeo_price);
    } else {
        echo "Product not found";
    }
} else {
    echo 'No product data in the  array.';
}


