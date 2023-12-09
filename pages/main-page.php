<?php
$API_URL = "http://localhost:3001";
?>
<div class="wrap">
    <h1 class="wp-heading-inline">Storreo Main Page</h1>


    <!-- Add Products Button -->
    <button id="addProductsButton" style="background-color: red; color: white;">Add Products From Suppliers</button>

    <a target="_blank" href="http://localhost:3000" class="btn">Connect Your Store</a>



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




