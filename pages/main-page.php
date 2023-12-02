<div class="wrap">
    <h1 class="wp-heading-inline">Storreo Main Page</h1>


    <!-- Add Products Button -->
    <button id="addProductsButton" style="background-color: red; color: white;">Add Products From Suppliers</button>

    <button class="connect">Connect Your Store</button>



    <div class="grid-container">
        <!-- Top Row -->
        <div class="grid-item top-left">
            <!-- Add Analytics Chart Here -->
            <p>Analytics Chart</p>
        </div>
        <div class="grid-item top-right">
            <!-- Total Sells and Profit Information -->
            <h3>Total Sells</h3>
            <p>Daily: 2 sells</p>
            <p>Monthly: 10 sells</p>
            <p>Your Profit: $2000</p>
        </div>

        <!-- Bottom Row -->
        <div class="grid-item bottom-left">
            <!-- Table with Provider, SKU, Image, Total Sells -->
            <h3>Sales Table</h3>
            <table>
                <thead>
                    <tr>
                        <th>Provider</th>
                        <th>SKU</th>
                        <th>Image</th>
                        <th>Total Sells</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Provider 1</td>
                        <td>SKU123</td>
                        <td><img src="image1.jpg" alt="Product Image"></td>
                        <td>50</td>
                    </tr>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>

        <div class="grid-item bottom-right">
            <!-- Last Info and Messages List -->
            <h3>Last Info and Messages</h3>
            <ul>
                <li>Lorem Ipsum 1</li>
                <li>Lorem Ipsum 2</li>
                <li>Lorem Ipsum 3</li>
                <li>Lorem Ipsum 4</li>
                <li>Lorem Ipsum 5</li>
            </ul>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Supplier Products</h2>
        <!-- Table for Supplier Products -->
        <table>
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Shipping Info</th>
                    <th>Add Product</th>
                </tr>
            </thead>
            <tbody>
                <!-- Populate the table rows dynamically using JavaScript -->
            </tbody>
        </table>
    </div>
</div>


<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("addProductsButton");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function () {
        modal.style.display = "block";
        // You can fetch and populate the table rows dynamically here
        // For simplicity, let's add a sample row
        var tbody = document.querySelector('tbody');
        tbody.innerHTML = '<tr><td>Supplier 1</td><td><img src="image.jpg" alt="Product Image"></td><td>Product 1</td><td>SKU123</td><td>50</td><td>$20</td><td>Free Shipping</td><td><button onclick="addProduct()">Add</button></td></tr>';
    }

    // When the user clicks on <span> (x), close the modal
    function closeModal() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Function to simulate adding a product (you can implement your logic here)
    function addProduct() {
        alert("Product added!");
    }
</script>


<style>
    .connect{
        background: green;
    }
    /* Add your CSS styles for grid layout here */
    .grid-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 20px;
    }

    .grid-item {
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }


    /* Add your CSS styles for grid layout here */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
</style>
