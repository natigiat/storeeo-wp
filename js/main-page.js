jQuery(document).ready(function ($) {
  // Get the modal
  var modal = $("#myModal");

  // Get the button that opens the modal
  var btn = $("#addProductsButton");

  // Get the <span> element that closes the modal
  var span = $(".close")[0];

  // When the user clicks on the button, open the modal
  btn.on("click", async function () {
    modal.css("display", "block");

    // You can fetch and populate the table rows dynamically here
    // For simplicity, let's add a sample row
    var tbody = $("tbody");
    tbody.html(
      '<tr><td>Supplier 1</td><td><img src="image.jpg" alt="Product Image"></td><td>Product 1</td><td>SKU123</td><td>50</td><td>$20</td><td>Free Shipping</td><td><button onclick="addProduct()">Add</button></td></tr>'
    );

    try {
      const products = await getProducts();
      // Do something with the products data
    } catch (error) {
      // Handle errors
    }
  });

  // When the user clicks on <span> (x), close the modal
  $(".close").on("click", function () {
    modal.css("display", "none");
  });

  // When the user clicks anywhere outside of the modal, close it
  $(window).on("click", function (event) {
    if (event.target == modal[0]) {
      modal.css("display", "none");
    }
  });

  // Function to simulate adding a product (you can implement your logic here)
  function addProduct() {
    alert("Product added!");
  }

  // get products
  async function getProducts() {
    var url = "http://localhost:3001/products";

    var headers = new Headers();
    headers.append(
      "Cookie",
      "connect.sid=s%3A0oKVNptxcHZSdCLm-h1HGRR0rtrdc898.s%2FvGmIXY4kAdVYu5PxUc006ah6eMurrCShZz085jdVk"
    );

    var requestOptions = {
      method: "GET",
      headers: headers,
      redirect: "follow",
    };

    try {
      const response = await fetch(url, requestOptions);
      const data = await response.json();
      return data;
    } catch (error) {
      console.error("Error:", error);
      throw error;
    }
  }
});
