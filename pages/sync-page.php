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
class Storreo_Main_Table extends WP_List_Table {
    
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
            'image'      => 'Image',
            'name'       => 'Name',
            'sku'        => 'SKU',
            'stock'      => 'Stock',
            'price'      => 'Price',
            'top-sellers' => 'Top Sellers',
            'revenue'       => 'Revenue',
            'analytics'       => 'Analytics',
            'sync'       => 'Sync',
            'post_content' => 'Post Content', 
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
            if( empty( $storeeo_watching ) ) {
                $data[] = array(
                    'id'         => $product->get_id(),
                    'image'      => $product->get_image(),
                    'name'       => $product->get_name(),
                    'sku'        => $product->get_sku(),
                    'stock'      => $product->is_in_stock(),
                    'price'      =>  intval($storeeo_price),
                    'top-sellers' => implode(', ', wp_list_pluck($product->get_category_ids(), 'name')),
                    'revenue'     => implode(', ', wp_list_pluck($product->get_tag_ids(), 'name')),
                    'analytics'   => "",
                    'sync'        =>"",
                    'post_content' => $product->get_description(),
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
                return $item["stock"] ? "<button class='btn'>Share</button>" : "<a target='_blank' href='./post.php?post=".$item['id']."&action=edit'>Edit Product</a>"; 

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
    $table = new Storreo_Main_Table();
    $table->prepare_items();
    $table->search_box('Search', 'search');
    $table->display();
    ?>
</div>




