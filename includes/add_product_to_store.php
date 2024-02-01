<?php
// if ( ! defined( 'ABSPATH' ) ) exit;

include './file_downloader.php';
require_once("../../../../wp-load.php");
require_once("./validate.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product'])) {
    $product_data = isset($_POST['product']) ? sanitize_post_data($_POST['product']) : null;
    $store_price = isset($_POST['store_price']) ? sanitize_post_data($_POST['store_price']) : null;
    
 
    $mainImage =  storeeo_upload_from_url($product_data['product_main_image']);
  


    $product = new WC_Product_Simple();
    $product->set_name( $product_data['product_title'] );
    $product->set_short_description( $product_data['product_content']); 
    $product->set_catalog_visibility( 'visible' );
    $product->set_price( $store_price);
    $product->set_regular_price($store_price);
    $product->set_sold_individually( true );
    $product->set_image_id(  $mainImage );
    if( $product_data['product_sku'] ) {
        $product->set_sku(  !storeeo_is_sku_unique($product_data['product_sku']) ?  $product_data['product_sku']+01  : $product_data['product_sku']);
    }
 
    if($product_data['product_quantity'] > 0){
        $product->set_manage_stock(true);
        $product->set_stock_quantity($product_data['product_quantity']? $product_data['product_quantity'] : null);
    }else{
        $product->set_stock_status('instock');
    }
 
   
    $product->set_downloadable( false );
    $product_id   = $product->save();
    $product->set_status('publish');


    update_post_meta( $product_id, 'storeeo_watching', 'true' );
    update_post_meta( $product_id, 'storeeo__id', $product_data['product_id'] );
    update_post_meta( $product_id, '_custom_product_storeeo_price', $product_data['product_storeeo_price'] );





    // Check if the product was saved successfully
    if ($product_id) {
        // Debug information
        echo 'Product created successfully. Product ID: ' . esc_attr($product_id);
    } else {
        // Debug information
        echo 'Error creating product.';
    }
} else {
    // Debug information
    echo 'No product data added.';
}

