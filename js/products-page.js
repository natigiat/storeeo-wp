jQuery(document).ready(async function ($) {
  getProducts();

  //add product onClick
  $("body").on("click", ".add-product", function (e) {
    const item = $(this);
    const product_data = $(this).closest("tr").data("product");
    addProductToStore(item, product_data);
  });

  $(".no-items").hide();

  async function getProducts() {
    const allProducts = await getRecord("products");
    const tableBody = $("#the-list");

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
      newRow.append(`<td>${product.product_regular_price}</td>`);
      newRow.append(`<td>${product.product_storeeo_price}</td>`);
      newRow.append(
        `<td><button>${discount_percentage.toFixed(2)}</button></td>`
      );

      newRow.append(
        `<td class="flex">   
        <img class="shop_logo" src=${product.shop.shop_logo}/><a target="_blank" href=${product.shop.shop_url}>${product.shop.shop_url}</a></td>`
      );
      newRow.append(`<td>0</td>`);
      newRow.append(`<td>No Shipping</td>`);
      newRow.append(`<td>  <button class='btn add-product'>Add</button></td>`);
      tableBody.append(newRow);
    });
  }

  async function addProductToStore(item, product) {
    console.log({ product });

    $.ajax({
      type: "POST",
      url: ajax_call.add_product_to_store,
      data: {
        product: product,
      },
      success: function (response) {
        $(".success").remove();
        item.after(`<div class="success">Product Added To Your Store</div>`);
      },
    });
  }
});
