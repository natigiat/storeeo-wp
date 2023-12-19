<?php

include './file_downloader.php';
define('WP_USE_THEMES', false);
require_once("../../../../wp-load.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['user'])) {
 $user = $_POST['user'];
 $_SESSION['user'] = serialize($user);
}


// Function to check if SKU is unique
function is_sku_unique($sku) {
    $product = wc_get_product_id_by_sku($sku);
    return empty($product);
}