<?php
// if ( ! defined( 'ABSPATH' ) ) exit;

require_once(ABSPATH . 'wp-includes/formatting.php');

function sanitize_post_data($data) {
    if (is_array($data)) {
        return array_map('sanitize_text_field', $data);
    } else {
        // Add the missing import statement
        return sanitize_text_field($data);
    }
}


// Function to check if SKU is unique
function storeeo_is_sku_unique($sku) {
    $product = wc_get_product_id_by_sku($sku);
    return empty($product);
}

?>