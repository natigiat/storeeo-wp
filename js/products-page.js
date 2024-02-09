jQuery(document).ready(async function ($) {
  // Initial pagination
  let itemsPerPage = 10;
  $("#itemsPerPage").val(itemsPerPage);
  let currentPage = 1;
  getProducts(currentPage, itemsPerPage);

  //add product onClick
  $("body").on("click", ".add-product", function (e) {
    const item = $(this);
    $(".error").remove();
    const product_data = $(this).closest("tr").data("product");
    const product_storeeo_id = product_data["product_id"];

    const store_price = Number(
      $(this).closest("tr").find(".store_price").val()
    );
    const storeeo_price = Number(
      $(this).closest("tr").find(".storeeo_price").text()
    );

    if (!store_price) {
      $(this).after("<div class='error'>Please set your price</div>");
    } else if (store_price && store_price < storeeo_price) {
      $(this).after(
        "<div class='error'>Your price must be greater than storeeo price</div>"
      );
    } else {
      addProductToStore(item, product_storeeo_id, store_price);
    }
  });

  $(".no-items").hide();

  async function getProducts(pageNumber, itemsPerPage) {
    const allProducts = await getRecord(
      `products?pageNumber=${pageNumber}&itemsPerPage=${itemsPerPage}`
    );

    const tableBody = $("#the-list");

    tableBody.empty(); // Clear previous content

    allProducts.forEach((product) => {
      var discount_percentage =
        ((product.product_regular_price - product.product_storeeo_price) /
          product.product_regular_price) *
        100;

      const product_data = JSON.stringify(product);
      const newRow = $(`<tr data-product='${product_data}'>`);
      newRow.append(`<td> <input type="checkbox" value="14"></td>`);
      newRow.append(`<td><img src="${product.product_main_image}" /></td>`);
      newRow.append(`<td>${product.product_title}</td>`);
      newRow.append(
        `<td>${
          product.product_in_stock ? "<div class='green'>In Stock</div>" : "-"
        }</td>`
      );
      newRow.append(
        `<td>${product.product_quantity ? product.product_quantity : "-"}</td>`
      );
      newRow.append(
        `<td class="regular_price">${product.product_regular_price}</td>`
      );
      newRow.append(
        `<td class="storeeo_price">${product.product_storeeo_price}</td>`
      );
      newRow.append(
        `<td><button>%${discount_percentage.toFixed(2)}</button></td>`
      );
      newRow.append(
        `<td><input type="number" class="store_price" placeholder="Supplier price"></input></td>`
      );
      newRow.append(
        `<td class="flex">   
       <a target="_blank" href=${product.shop.shop_url}> <img class="shop_logo" src=${product.shop.shop_logo}/></a></td>`
      );
      newRow.append(`<td>0</td>`);
      newRow.append(`<td>No Shipping</td>`);
      newRow.append(`<td>  <button class='btn add-product'>Add</button></td>`);
      tableBody.append(newRow);
    });
  }

  // Pagination controls
  $("#previousPage").click(() => {
    currentPage--;
    getProducts(currentPage, itemsPerPage);
  });

  $("#nextPage").click(() => {
    currentPage++;
    getProducts(currentPage, itemsPerPage);
  });

  $("#itemsPerPage").on("change", function (e) {
    itemsPerPage = $("#itemsPerPage").val();

    console.log({ itemsPerPage });
    getProducts(currentPage, itemsPerPage);
  });

  async function addProductToStore(item, product_storeeo_id, store_price) {
    $.ajax({
      type: "POST",
      url: ajax_call.add_product_to_store,
      data: {
        product_storeeo_id: product_storeeo_id,
        store_price: store_price,
      },
      success: function (response) {
        $(".success").remove();
        item.after(`<div class="success">Product Added To Your Store</div>`);
      },
    });
  }

  // add filters

  $(".filters").append(`
  


    <!-- Search input -->
    <input type="text" id="product_search" placeholder="Search Products">

    <!-- Dropdown of Categories -->
    <select id="product_category">
        <option value="">All Categories</option>
        <option value="1">Category 1</option>
        <option value="2">Category 2</option>
        <option value="3">Category 3</option>
    </select>


   
    <!-- Select for Country -->
    <select id="country_select">
       <option value="">All Countries</option>
        <option value="israel">Israel</option>
        <option value="usa">United States</option>
    </select>
    
    <!-- Price Range Slider -->
    <div id="slider" class="slider"></div>

    `);

  setTimeout(() => {
    $(document)
      .find("#slider")
      .slider({
        range: true, // Enable range
        min: 0, // Minimum value
        max: 1000, // Maximum value
        step: 100, // Step size
        values: [0, 1000], // Initial values
        slide: function (event, ui) {
          // Update the input fields or perform other actions as needed
          $("#minValue").val(ui.values[0]);
          $("#maxValue").val(ui.values[1]);
        },
      });
  }, 200);
});
