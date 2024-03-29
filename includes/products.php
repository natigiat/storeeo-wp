<?php

require_once(ABSPATH . 'wp-includes/theme.php');

function  product_to_storeeo_function($product , $storreo_price=null){
    global $API;



    $logo_id = get_theme_mod('custom_logo');
    $logo_url = wp_get_attachment_image_src($logo_id, 'full');



    $product_gallery_ids = $product->get_gallery_image_ids();
    $product_gallery_images = array();

    foreach ($product_gallery_ids as $gallery_id) {
        $image_url = wp_get_attachment_url($gallery_id);
        $product_gallery_images[] = $image_url;
    }


    $product_variants = array();

    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();

        foreach ($variations as $variation) {
            $product_variants[] = array(
                'variant_id'       => $variation['variation_id'],
                'variant_title'    => implode(', ', $variation['attributes']),
                'variant_price'    => (float) $variation['display_price'],
                'variant_quantity' => (float) $variation['max_qty'],
            );
        }
    }

    $storreo_price = $storreo_price ? $storreo_price :get_post_meta($product->id, "_custom_product_storeeo_price", true);



    $productInfo = array(
        'product_title'         => $product->get_name(),
        'product_content'       => $product->get_description(),
        'product_visibility'    => true, // You may need to customize this based on your criteria
        'product_price'         => (float)$product->get_price(),
        'product_quantity'      => (float)$product->get_stock_quantity() ? $product->get_stock_quantity() : 0,
        'product_sku'           => $product->get_sku(),
        'product_main_image'    => wp_get_attachment_url( $product->get_image_id()),
        'product_store_id'      =>(float)$product->id,
        'product_regular_price' => (float)$product->get_price(),
        'product_storeeo_price' => (float)$storreo_price,
        'logo_url'              => $logo_url ? $logo_url['url'] : "",
        'product_visibility'   => true
    );

    // Check if product gallery images exist
    if (!empty($product_gallery_images)) {
        $productInfo['product_gallery_images'] = $product_gallery_images;
    }

    // Check if product variants exist
    if (!empty($product_variants)) {
        $productInfo['product_variants'] = $product_variants;
    }



    // Define additional headers
    $headers = array(
        'Content-Type' => 'application/json', 
        'shop_url'=>home_url(),
    );

    $url = $API."/products";

    $response = wp_remote_post(
        $url,
        array(
            'body'    => json_encode($productInfo),
            'headers' => $headers,
        )
    );



    if (is_wp_error($response)) {
        // Handle error
        echo 'Request failed: ' . esc_html($response->get_error_message());
    } else {
        // Successful request
        $body = wp_remote_retrieve_body($response);
        $decoded_body = json_decode($body, true); // If the response is in JSON format
        $product_storeeo_id = $decoded_body['product_id'];
        // Check if "product_id" is set before echoing
        if (isset($product_storeeo_id)) {
            $result = update_post_meta($product->id, 'storeeo_sync_id', 6);

            // if ($result === false) {
            //     // There was an error
            //     echo "Failed to update metadata: " . get_post_meta($product->id, 'storeeo_sync_id', true);
            // } else {
            //     // Successful update
            //     echo "Metadata updated successfully!" .  get_post_meta($product->id, 'storeeo_sync_id', true);
            // }
        }
    }
}

