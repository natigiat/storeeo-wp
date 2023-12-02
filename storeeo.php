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
    add_submenu_page('storreo-main', 'Sync', 'Sync', 'manage_options', 'storreo-sync', 'storreo_sync_page');
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