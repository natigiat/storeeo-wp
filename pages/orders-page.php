<div class="wrap">
    <h1 class="wp-heading-inline">Storreo Main Page</h1>

    <?php
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
                'cb'        => '<input type="checkbox" />',
                'image'     => 'Image',
                'name'      => 'Name',
                'sku'       => 'SKU',
                'stock'     => 'Stock',
                'price'     => 'Price',
                'categories'=> 'Categories',
                'tags'      => 'Tags',
                'date'      => 'Date',
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
    $table = new Storreo_Main_Table();
    $table->prepare_items();
    $table->search_box('Search', 'search');
    $table->display();
    ?>
</div>
