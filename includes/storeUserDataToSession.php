<?php
if ( ! defined( 'ABSPATH' ) ) exit;

include './file_downloader.php';
require_once("../../../../wp-load.php");
require_once("./validate.php");


// Check if the 'product' key exists in the $_POST array
if (isset($_POST['user'])) {
 $user = isset($_POST['user']) ? sanitize_post_data($_POST['user']) : null;
 $_SESSION['user'] = serialize($user);
}


