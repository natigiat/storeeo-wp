<?php
/*
Plugin Name: Storeeo
Description: Share Products betweern woocomerce stores.
Version: 1.0
Author: natigiat@gmail.com

 * Text Domain: elementor
 *
 * @package Elementor
 * @category Core
 *
 * Elementor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
*/



// Create the main menu and submenus
function storreo_plugin_menu() {
    add_menu_page('Storreo', 'Storreo', 'manage_options', 'storreo-main', 'storreo_main_page');
    add_submenu_page('storreo-main', 'Orders', 'Orders', 'manage_options', 'storreo-orders', 'storreo_orders_page');
    add_submenu_page('storreo-main', 'Products', 'Products', 'manage_options', 'storreo-products', 'storreo_products_page');
    add_submenu_page('storreo-main', 'Share Products', 'Share Your Products', 'manage_options', 'storreo-sync', 'storreo_sync_page');
    add_submenu_page('storreo-main', 'Payment', 'Payment', 'manage_options', 'storreo-payment', 'storreo_payment_page');
}

// Callback for the main menu page
function storreo_main_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/main-page.php');
}

// Callback for the Orders submenu
function storreo_orders_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/orders-page.php');
}

// Callback for the Products submenu
function storreo_products_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/products-page.php');
}

// Callback for the Sync submenu
function storreo_sync_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/sync-page.php');
}

// Callback for the Payment submenu
function storreo_payment_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/payment-page.php');
}

// Hook to add menu and submenus
add_action('admin_menu', 'storreo_plugin_menu');




function storreo_admin_styles() {
    // Define a unique handle for your admin style
    $handle = 'storeeo-admin-styles';

    // Define the URL to your plugin's admin stylesheet
    $style_url = plugins_url('css/style.css', __FILE__);

    // Define any dependencies (optional)
    $dependencies = array();

    // Define a version number to bust the cache (optional)
    $version = '1.0';

    // Enqueue the admin style
    wp_enqueue_style($handle, $style_url, $dependencies, $version);

    wp_enqueue_script('api', plugin_dir_url(__FILE__) . 'js/api.js', array('jquery'), null, true);
    wp_enqueue_script('general', plugin_dir_url(__FILE__) . 'js/general.js', array('jquery'), null, true);

    
    $current_screen = get_current_screen();
    error_log('Current Screen ID: ' . $current_screen->id);

    
    // Check if it's the Shippic page
   if (is_page('shippic')) {
     wp_enqueue_script('main-page', plugin_dir_url(__FILE__) . 'js/main-page.js', array('jquery'), null, true);
    }

    // Check if it's the Orders page
    if (is_page('orders')) {
        wp_enqueue_script('orders-page', plugin_dir_url(__FILE__) . 'js/orders-page.js', array('jquery'), null, true);
    }

    // Check if it's the Payment page
    if (is_page('payment')) {
        wp_enqueue_script('payment-page', plugin_dir_url(__FILE__) . 'js/payment-page.js', array('jquery'), null, true);
    }

    // Check if it's the Products page
    if (isset($_GET['page']) && $_GET['page'] === 'storreo-products') {
        wp_enqueue_script('products-page', plugin_dir_url(__FILE__) . 'js/products-page.js', array('jquery'), '1.0', true);
        wp_localize_script('products-page', 'ajax_call', array('add_product_to_store' => plugin_dir_url(__FILE__) . './includes/add_product_to_store.php'));
    }

    // Check if it's the Sync Page page
    if (isset($_GET['page']) && $_GET['page'] === 'storreo-sync') {
        wp_enqueue_script('sync-page', plugin_dir_url(__FILE__) . 'js/sync-page.js', array('jquery'), null, true);
    }

    
}

// Hook the enqueue function into the admin_enqueue_scripts action
add_action('admin_enqueue_scripts', 'storreo_admin_styles', 999);




add_filter( 'manage_edit-product_columns', 'bbloomer_admin_products_visibility_column', 9999 );
 
function bbloomer_admin_products_visibility_column( $columns ){
   $columns['storeeo_watching'] = '<span class="manage-column column-product_tag">Storeeo</span>';
   return $columns;
}


add_action( 'manage_product_posts_custom_column', 'bbloomer_admin_products_visibility_column_content', 10, 2 );

function bbloomer_admin_products_visibility_column_content( $column, $product_id ) {
    if ( $column == 'storeeo_watching' ) {
        $is_watching = get_post_meta( $product_id, 'storeeo_watching', true );

        // Check if the product is marked as watching
        if ( $is_watching === 'true' ) {
            echo '<span class="watching_btn">Watching</span>';
        } else {
            echo '-';
        }
    }
}


// The code for displaying WooCommerce Product Custom Fields
add_action( 'woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields' ); 
// Following code Saves  WooCommerce Product Custom Fields
add_action( 'woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save' );


function woocommerce_product_custom_fields () {
global $woocommerce, $post;
echo '<div class="product_custom_field">';
// Custom Product Text Field
woocommerce_wp_text_input(
    array(
        'id' => '_custom_product_storeeo_price',
        'class' => 'btn',
        'label' => __('Storeeo Price', 'woocommerce'),
        'desc_tip' => 'true'
    )
);

echo '</div>';

}


function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_product_storeeo_price = $_POST['_custom_product_storeeo_price'];
    if (!empty($woocommerce_custom_product_storeeo_price))
        update_post_meta($post_id, '_custom_product_storeeo_price', esc_attr($woocommerce_custom_product_storeeo_price));
}