<?php

include './file_downloader.php';
define('WP_USE_THEMES', false);
require_once("../../../../wp-load.php");

// Check if the 'product' key exists in the $_POST array
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $status = $_POST['status'];

    $sync_status = update_post_meta($product_id, 'storeeo_sync', $status);
    echo "The sync status for product ID $product_id is: $sync_status";

} else {
    // Debug information
    echo 'No product change status';
}


