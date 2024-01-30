<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Share Your Products</h1>
    <h4 class="">Share your Products, and get sale from another shops online</h4>
    <?php
    // Include the main class file for WP_List_Table
    // Include the main class file for WP_List_Table
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// Your main plugin class
class Storeeo_Main_Table extends WP_List_Table {
    
    // Constructor
    public function __construct() {
        parent::__construct(array(
            'singular' => 'product',
            'plural'   => 'products',
            'ajax'     => false,
        ));
    }

    // Define the columns for the table
    public function get_columns() {
        return array(
            'cb'         => '<input type="checkbox" />',
            'storeeo_sync_id' =>'Storeeo Id',
            'image'      => 'Image',
            'name'       => 'Name',
            'sku'        => 'SKU',
            'stock'      => 'Stock',
            'regular-price'     => 'Regular Price', 
            'storeeo-price'=> 'Storeeo Price', 
            'storeeo-discount'=> 'Discount', 
            'top-sellers' => 'Top Sellers',
            'sales'       => 'Sales',
            'sync'       => 'Sync',
            'post_content' => 'Post Content', 
            'product_id'=>'Product ID',
           
            
        );
    }

    // Define the sortable columns
    public function get_sortable_columns() {
        return array(
            'name' => array('name', true),
            'sku'  => array('sku', false),
            'stock' => array('stock', false),
            'price' => array('price', false),
        
        );
    }

    // Prepare the items for the table
    public function prepare_items() {
        // Your code to fetch products from WooCommerce
        $data = $this->get_woocommerce_products();

        // Sort data
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($this->get_columns(), array(), $sortable);
        usort($data, array($this, 'usort_reorder'));

        // Search
        $search = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
        if (!empty($search)) {
            $data = array_filter($data, function ($item) use ($search) {
                return stripos($item['name'], $search) !== false;
            });
        }

        // Pagination
        $per_page     = 10;
        $current_page = $this->get_pagenum();
        $total_items  = count($data);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ));


        $this->items = array_slice($data, ($current_page - 1) * $per_page, $per_page);
    }

    // WooCommerce API function to get products
    private function get_woocommerce_products() {
        $args = array(
            'status'     => 'publish', // Only retrieve published products
            'limit'      => -1,         // Retrieve all products
        );
        
        $products = wc_get_products($args);
    
        // Extract relevant product information
        $data = array();


        foreach ($products as $product) {



            $storeeo_watching   = $product->get_meta('storeeo_watching');
            $product_id = $product->get_id(); 
            $storeeo_price =get_post_meta($product_id, "_custom_product_storeeo_price", true);
            if (empty($storeeo_watching)) {
                $regular_price = $product->get_price(); // Default to the product price
            
                // Check if the product has variations
                if ($product->is_type('variable')) {
                    $variations = $product->get_available_variations();
            
                    if (!empty($variations)) {
                        // Use the price of the first variation as the regular price
                        $first_variation = reset($variations);
                        $regular_price = $first_variation['display_price'];
                    }
                }

            
                $storeeo_discount = '';
            
                if ($regular_price > 0) {
                    $storeeo_discount = number_format(($regular_price - $storeeo_price) / $regular_price * 100, 2) . "%";
                }
            
                $data[] = array(
                    'id' => $product->get_id(),
                    'image' => $product->get_image(),
                    'name' => $product->get_name(),
                    'sku' => $product->get_sku(),
                    'stock' => $product->is_in_stock(),
                    'regular-price' => $regular_price,
                    'storeeo-price' => $storeeo_price,
                    'storeeo-discount' => $storeeo_discount,
                    'top-sellers' => implode(', ', wp_list_pluck($product->get_category_ids(), 'name')),
                    'sales' => implode(', ', wp_list_pluck($product->get_tag_ids(), 'name')),
                    'sync' => "",
                    'post_content' => $product->get_description(),
                    'product_id' => $product_id,
                );
            }
            
        }
    
        return $data;
    }

    // Sort callback function
    private function usort_reorder($a, $b) {
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'name';
        $order   = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc';

        $result = strcmp($a[$orderby], $b[$orderby]);

        return ($order === 'asc') ? $result : -$result;
    }

    // Display the table rows
    public function display_rows() {
        foreach ($this->items as $item) {
            echo '<tr>';
            $this->single_row_columns($item);
            echo '</tr>';
        }
    }

    // Define the default column values
    protected function column_default($item, $column_name) {
        // Debugging: Output column name and item data for debugging purposes
        // var_dump($column_name);
        // var_dump($item);
       
        $post_content_value = '';

        $storeeo_sync =get_post_meta($item['id'], "storeeo_sync", true);
        $storeeo_sync_id =get_post_meta($item['id'], "storeeo_sync_id", true);

        
        $regular_price = floatval($item['regular-price']);
        $storeeo_price = floatval($item['storeeo-price']);
        
        // Check if the values are numeric before performing the calculation
        if (is_numeric($regular_price) && is_numeric($storeeo_price) && $regular_price > 0) {
            $storeeo_discount =  "%" .number_format(($regular_price - $storeeo_price) / $regular_price * 100, 2);
        } else {
            $storeeo_discount = 'Invalid input values'; // or handle the situation accordingly
        }        // var_dump($storeeo_sync);

        // Switch statement to handle different columns
        switch ($column_name) {
            case 'image':
                // Output an image tag for the 'image' column
                return $item[$column_name];

            case 'name':
                    // Output an image tag for the 'image' column
                    return "<a target='_blank' href='./post.php?post=".$item['id']."&action=edit'>".$item[$column_name]."</a>";

            case 'categories':
                return $item[$column_name] ? $item[$column_name] : "No Categories";

            case 'stock':
                return $item[$column_name] ?  '<div class="instock">In stock</div>' : "Out of stock";

            case 'tags':
                // Output the content for 'categories' and 'tags' columns
                return $item[$column_name] ? $item[$column_name] : "No Tags";

            case 'sync':
                // Output the content for 'categories' and 'tags' columns
                if($storeeo_sync  ==="true"){
                    return "<button class='btn btn-green connected-product'>Connected</button>";
                }else{
                    return $item["stock"]  ? "<button class='btn share-product'>Share</button>" : "<a target='_blank' href='./post.php?post=".$item['id']."&action=edit'>Edit Product</a>"; 
                }


            case 'storeeo-discount':
                return "<button class='storeeo-discount'>$storeeo_discount</button>";

            
            case 'storeeo_sync_id':
                return "<div class='storeeo-sync-id'>$storeeo_sync_id</div>";
                
                


            case 'post_content':
                $post_content_value = $item['post_content'];
                break;

            default:
                // Output the content for other columns, using esc_html to sanitize
                return isset($item[$column_name]) ? esc_html($item[$column_name]) : '';
        }
        $output = '<div class="hidden_data">' . $post_content_value . '</div>';

        return $output;
    }

    // Define the checkbox column
    protected function column_cb($item) {
        return '<input type="checkbox" value="' . esc_attr($item['ID']) . '" />';

    }


}

    // Instantiate the table class and display the table
    $table = new Storeeo_Main_Table();
    $table->prepare_items();
    $table->search_box('Search', 'search');
    $table->display();

    $logo_id = get_theme_mod('custom_logo');
    $logo_url = wp_get_attachment_image_src($logo_id, 'full');

    if ($logo_url) {
        echo '<input  class="shop_logo" type="hidden" data-logo="' . esc_url($logo_url[0]) . '"  />';
    }
    ?>
</div>




