<div class="wrap">
    <h1 class="wp-heading-inline">Storeeo Main Page</h1>

    <?php
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
                'cb'        => '<input type="checkbox" />',
                'order_id'           => 'Order ID',
                'order_amount'          => 'Order Amount',
                'order_product_prices'  => 'Supplier Price',
                'order_your_profit'     => 'Order Profit',
                'order_items'        => 'Order Items',
                'order_user_info'    => 'User Info',
                'phone'              => 'Phone',
                'order_date_created' => 'Order Date Created',
                'order_status'       => 'Order Status',
                'order_action' => 'Action',
            );
        }
    
        // Define the sortable columns
        public function get_sortable_columns() {
            return array(
                'name'      => array('name', true),
                'sku'       => array('sku', false),
                'stock'     => array('stock', false),
                'price'     => array('price', false),
                'date'      => array('date', true),
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
            return isset($item[$column_name]) ? $item[$column_name] : '';
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
