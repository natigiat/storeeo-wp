
<div class="wrap">
    <h1 class="wp-heading-inline">Products</h1>
    <h4 class="">Add Products from other supplier to your store</h4>
<?php 
     $current_file = basename(__FILE__);
    
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
                'cb'            => '<input type="checkbox" />',
                'image'         => 'Image',
                'name'          => 'Name',
                'stock'         => 'Stock',
                'quantity'         => 'Quantity',
                'regular-price' => 'Regular Price',
                'storeeo-price'=> 'Storeeo Price', 
                'storeeo-discount'=> 'Discount', 
                'your-price'=> 'Your Price', 
                'shop_seller'   => 'Shop',
                'total_sales'   => 'Total Sales',
                'shipping'          => 'Shipping',
                'add'           =>  'Action',
            );
        }
    
        // Define the sortable columns
        public function get_sortable_columns() {
            return array(
                'name'      => array('name', true),
                'stock'     => array('stock', false),
                'price'     => array('price', false),
                'total_sales'      => array('date', true),
            );
        }
    
        // Prepare the items for the table
        public function prepare_items() {
            // Your code to fetch products goes here
            $data = array(); // Replace with your actual data
    
            $per_page     = 10;
            $current_page = $this->get_pagenum();
            $total_items  = count($data);
    
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page'    => $per_page,
            ));
    
            $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
    
            $this->items = array_slice($data, ($current_page - 1) * $per_page, $per_page);
        }

        public function set_items($data) {
            $this->items = $data;
            $this->prepare_items();
        }

    
        // Display the table rows
        public function display_rows() {
            foreach ($this->items as $item) {
                echo '<tr>';
                $this->single_row_columns($item);
                echo '</tr>';
            }
        }
    
      

        protected function column_default($item, $column_name) {
            // Debugging: Output column name and item data for debugging purposes
            // var_dump($column_name);
            // var_dump($item);
        
            // Switch statement to handle different columns
            $storeeo_watching =get_post_meta($item['id'], "storeeo_watching", true);
         

            switch ($column_name) {
                case 'image':
                    // Output an image tag for the 'image' column
                    return $item[$column_name];
                case 'categories':
                    return $item[$column_name] ? $item[$column_name] : "No Categories";
    
                case 'stock':
                        return $item[$column_name] ? $item[$column_name] : "Out of stock";
    
              
    
                case 'add':
                    // Output the content for 'categories' and 'tags' columns
                    if($storeeo_watching  ==="true"){
                        return "<button class='connected-product'>Watching</button>";
                    }else{
                        return "<button class='btn'>Share</button>"; 
                    }


                case 'your-price':
                        return "woo";
                    
                default:
                    // Output the content for other columns, using esc_html to sanitize
                    return isset($item[$column_name]) ? esc_html($item[$column_name]) : '';
            }
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
    ?>
</div>



