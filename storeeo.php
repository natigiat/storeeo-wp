<?php
/*
Plugin Name: Storeeo
Description: Share Products betweern woocomerce stores.
Version: 1.0.0
Author: natigiat@gmail.com
License: GPLv2 or later
*/

session_start();

// Create the main menu and submenus
function storeeo_plugin_menu() {
    add_menu_page('Storeeo', 'Storeeo', 'manage_options', 'storeeo-main', 'storeeo_main_page');
    add_submenu_page('storeeo-main', 'Orders', 'Orders', 'manage_options', 'storeeo-orders', 'storeeo_orders_page');
    add_submenu_page('storeeo-main', 'Products', 'Products', 'manage_options', 'storeeo-products', 'storeeo_products_page');
    add_submenu_page('storeeo-main', 'Share Products', 'Share Your Products', 'manage_options', 'storeeo-sync', 'storeeo_sync_page');
}


global $API;
$API = "http://localhost:3001";

include_once(plugin_dir_path(__FILE__) . 'includes/products.php');

// Callback for the main menu page
function storeeo_main_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/main-page.php');
}

// Callback for the Orders submenu
function storeeo_orders_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/orders-page.php');
}

// Callback for the Products submenu
function storeeo_products_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/products-page.php');
}

// Callback for the Sync submenu
function storeeo_sync_page() {
    include_once(plugin_dir_path(__FILE__) . 'pages/sync-page.php');
}


// Hook to add menu and submenus
add_action('admin_menu', 'storeeo_plugin_menu');




function storeeo_admin_styles() {
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
    wp_localize_script('general', 'ajax_call', array('storeUserDataToSession' => plugin_dir_url(__FILE__) . './includes/storeUserDataToSession.php'));

    
    $current_screen = get_current_screen();
    error_log('Current Screen ID: ' . $current_screen->id);

    
    // Check if it's the Shippic page
   if (is_page('shippic')) {
     wp_enqueue_script('main-page', plugin_dir_url(__FILE__) . 'js/main-page.js', array('jquery'), null, true);
    }

    // Check if it's the Orders page
    if (isset($_GET['page']) && $_GET['page'] === 'storeeo-orders') {
        wp_enqueue_script('orders-page', plugin_dir_url(__FILE__) . 'js/orders-page.js', array('jquery'), null, true);
    }

    // Check if it's the Payment page
    if (is_page('payment')) {
        wp_enqueue_script('payment-page', plugin_dir_url(__FILE__) . 'js/payment-page.js', array('jquery'), null, true);
    }

    // Check if it's the Products page
    if (isset($_GET['page']) && $_GET['page'] === 'storeeo-products') {
        wp_enqueue_script('products-page', plugin_dir_url(__FILE__) . 'js/products-page.js', array('jquery'), '1.0', true);
        wp_localize_script('products-page', 'ajax_call', array('add_product_to_store' => plugin_dir_url(__FILE__) . './includes/add_product_to_store.php'));
    }

    // Check if it's the Sync Page page
    if (isset($_GET['page']) && $_GET['page'] === 'storeeo-sync') {
        wp_enqueue_script('sync-page', plugin_dir_url(__FILE__) . 'js/sync-page.js', array('jquery'), null, true);
        wp_localize_script('sync-page', 'ajax_call', array(
        'change_product_sync_status' => plugin_dir_url(__FILE__) . './includes/change_product_sync_status.php' ,
        'add_product_to_storeeo' => plugin_dir_url(__FILE__) . './includes/add_product_to_storeeo.php'
    ));

        
    }

    
}

// Hook the enqueue function into the admin_enqueue_scripts action
add_action('admin_enqueue_scripts', 'storeeo_admin_styles', 999);




add_filter( 'manage_edit-product_columns', 'bbloomer_admin_products_visibility_column', 9999 );
 
function bbloomer_admin_products_visibility_column( $columns ){
   $columns['storeeo_watching'] = '<span class="manage-column column-product_tag">Storeeo</span>';
   $columns['storeeo_sells'] = '<span class="manage-column column-product_tag">Storeeo Sells</span>';
   $columns['storeeo_messages'] = '<span class="manage-column column-product_tag">Storeeo Messages</span>';
   return $columns;
}


add_action( 'manage_product_posts_custom_column', 'bbloomer_admin_products_visibility_column_content', 10, 2 );

function bbloomer_admin_products_visibility_column_content( $column, $product_id ) {
    $is_watching = get_post_meta( $product_id, 'storeeo_watching', true );
    if ( $column == 'storeeo_watching' ) {
        
        $storeeo__id = get_post_meta( $product_id, 'storeeo__id', true );
        
        // Check if the product is marked as watching
        if ( $is_watching === 'true' ) {
            echo '<span class="watching_btn" data-id="'.$storeeo__id.'" >Watching</span>';
        } else {
            echo '-';
        }
    }

    if ( $column == 'storeeo_sells' ) {
        $storee_sells_count= false;

        if ( $is_watching !== 'true' ) {
             echo '-';
        }else{
            if ( $storee_sells_count ) {
                echo '<span class="watching_btn" data-id="'.$storeeo__id.'" >Watching</span>';
            } else {
                echo '0';
            }
        }
        
    }

    if ( $column == 'storeeo_messages' ) {
        $storee_sells_count= false;
        if ( $is_watching !== 'true' ) {
            echo '-';
       }else{
           if ( $storee_sells_count ) {
               echo '<span class="watching_btn" data-id="'.$storeeo__id.'" >Watching</span>';
           } else {
               echo '0';
           }
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


add_action('woocommerce_thankyou', function ($order_id) {
    global $API;

    $order = new WC_Order($order_id);

    // Extract all properties from the Sequelize model into an associative array
    $orderData = array(
        'firstname' => $order->get_shipping_address_1(),
        'lastname' => $order->get_shipping_address_2(),
        'first_name' => $order->get_shipping_first_name(),
        'last_name' => $order->get_shipping_last_name(),
        'address_1' => $order->get_shipping_address_1(),
        'address_2' => $order->get_shipping_address_2(),
        'city' => $order->get_shipping_city(),
        'postcode' => $order->get_shipping_postcode(),
        'email' => $order->get_billing_email(),  // Note: Using billing email for the example, change if needed
        'phone' => $order->get_billing_phone(),  // Note: Using billing phone for the example, change if needed
        'payment_method_title' => $order->get_payment_method_title(),
        'transaction_id' => $order->get_transaction_id(),
        'amount' => floatval($order->get_total()),
        'order_key' => $order->get_order_key(),
    );

    // echo '<pre>';
    // var_dump($order);
    // echo '</pre>';


    $products_id = [];
    $order_items = $order->get_items();

    foreach ($order_items as $item_id => $item_data) {
        $product_id= $item_data->get_product_id();
        $storeeo_id = get_post_meta($product_id, 'storeeo__id', true);

        if (!empty($storeeo_id)) {
            $products_id[] = $storeeo_id;
        }

    }



    if (isset($_SESSION['user']) && !empty($products_id)) {
        if ($order->status != 'failed') {
            $storedUser = unserialize($_SESSION['user']);
            $url = $API."/orders";

            $orderData['products_id'] = $products_id;
            
            // Define additional headers
            $headers = array(
                'Content-Type' => 'application/json', 
                'user_token' => $storedUser['user_token'], 
                'shop_url'=>home_url(),
            );
    
            $response = wp_remote_post(
                $url,
                array(
                    'body' => json_encode($orderData), 
                    'headers' => $headers,
                )
            );
    
            echo '<pre>';
            var_dump($response);
            echo '</pre>';
        }
    }
    
});



add_action('save_post', 'update_product_on_storion');

function update_product_on_storion($post_id) {
    if ('product' === get_post_type($post_id)) {
        // Get the product object
        $product = wc_get_product($post_id);
        if ($product) {
            add_product_to_storeeo_function($product);
        }
    }
    
}






function custom_cart_init_actions() {
    // Check if it's the cart page
    if (is_cart()) {
        $pid_param  = isset($_GET['pid']) ? $_GET['pid'] : null;
        $json_data_fixed = preg_replace('/([{,])([a-zA-Z0-9_]+):/', '$1"$2":', $pid_param);
        $products_data = json_decode(urldecode($json_data_fixed), true);
        $storeeo_checkout = isset($_GET['storeeo_checkout']) ? $_GET['storeeo_checkout'] : false;

  

        if ($storeeo_checkout) {
            WC()->cart->empty_cart();

       
    
            foreach ($products_data as $product) {

                foreach ($product as $product_id => $quantity) {
               
                    // wp wc change price before adding to cart
                    $custom_field_name = "_custom_product_storeeo_price";
                    $storreo_price = get_post_meta($product_id, $custom_field_name, true);

                    // Make sure $quantity is a positive integer
                    $quantity = absint($quantity);
        
                    // Add product to cart
                    WC()->cart->add_to_cart($product_id, $quantity, 0, array(), array('storeeo_price' => $storreo_price));
                }
            }
    
            // Save cart changes to session
            WC()->cart->set_session();
    
            // Redirect to checkout page
            wp_redirect(wc_get_checkout_url());
            exit();
        }
    }
}

add_action('wp', 'custom_cart_init_actions');



add_action( 'woocommerce_before_calculate_totals', 'rudr_custom_price_refresh' );

function rudr_custom_price_refresh( $cart_object ) {

	foreach ( $cart_object->get_cart() as $item ) {

		if( array_key_exists( 'storeeo_price', $item ) ) {
			$item[ 'data' ]->set_price( $item[ 'storeeo_price' ] );
		}
      
	}
	
}