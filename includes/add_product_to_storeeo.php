<?php

include './file_downloader.php';
define('WP_USE_THEMES', false);
require_once("../../../../wp-load.php");
include_once('./products.php');


// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product_store_id'])) {
    $product_store_id = $_POST['product_store_id'];
    // Get the product object
    $product = wc_get_product($product_store_id);
    // Check if the product exists
    if ($product) {
        add_product_to_storeeo_function($product);
    } else {
        echo "Product not found";
    }
} else {
    echo 'No product data in the $_POST array.';
}


