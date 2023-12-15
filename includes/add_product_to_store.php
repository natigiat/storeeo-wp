<?php

include './file_downloader.php';
define('WP_USE_THEMES', false);
require_once("../../../../wp-load.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product'])) {
    $product_data = $_POST['product'];

    $mainImage =  rs_upload_from_url($product_data['product_main_image']);
  
  

    $product = new WC_Product_Simple();
    $product->set_name( $product_data['product_title'] );
    $product->set_short_description( $product_data['product_content']); 
    $product->set_catalog_visibility( 'visible' );
    $product->set_price( $product_data['product_price']);
    $product->set_regular_price($product_data['product_price']);
    $product->set_sold_individually( true );
    $product->set_image_id(  $mainImage );
    if( $product_data['product_sku'] ) {
        $product->set_sku(  !is_sku_unique($product_data['product_sku']) ?  $product_data['product_sku']+01  : $product_data['product_sku']);
    }
    $product->set_manage_stock(true);
    $product->set_stock_quantity($product_data['product_quantity']);
    // $product->set_stock_status('outofstock');
    $product->set_downloadable( false );
    $product_id   = $product->save();
    $product->set_status('publish');


    update_post_meta( $product_id, 'storeeo_watching', 'true' );
    update_post_meta( $product_id, 'storeeo__id', $product_data['product_id'] );





    // Check if the product was saved successfully
    if ($product_id) {
        // Debug information
        echo 'Product created successfully. Product ID: ' . $product_id;
    } else {
        // Debug information
        echo 'Error creating product.';
    }
} else {
    // Debug information
    echo 'No product data in the $_POST array.';
}


// Function to check if SKU is unique
function is_sku_unique($sku) {
    $product = wc_get_product_id_by_sku($sku);
    return empty($product);
}