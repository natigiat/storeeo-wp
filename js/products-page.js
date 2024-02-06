jQuery(document).ready(async function ($) {
  getProducts();

  //add product onClick
  $("body").on("click", ".add-product", function (e) {
    const item = $(this);
    $(".error").remove();
    const product_data = $(this).closest("tr").data("product");
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
      addProductToStore(item, product_data, store_price);
    }
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

  async function addProductToStore(item, product, store_price) {
    $.ajax({
      type: "POST",
      url: ajax_call.add_product_to_store,
      data: {
        product: product,
        store_price: store_price,
      },
      success: function (response) {
        $(".success").remove();
        item.after(`<div class="success">Product Added To Your Store</div>`);
      },
    });
  }
});
