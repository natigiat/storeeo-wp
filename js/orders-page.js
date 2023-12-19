jQuery(document).ready(async function ($) {
  getOrders();

  // add product onClick
  $("body").on("click", ".add-product", function (e) {
    const item = $(this);
    const product_data = $(this).closest("tr").data("product");
    addProductToStore(item, product_data);
  });

  $(".no-items").hide();

  async function getOrders() {
    const allOrders = await getRecord("orders");
    const tableBody = $("#the-list");

    console.log({ allOrders });
    allOrders.forEach((product) => {
      const product_data = JSON.stringify(product);
      const newRow = $(`<tr data-product='${product_data}'>`);
      newRow.append(`<td> <input type="checkbox" value="14"></td>`);
      newRow.append(`<td>${product.order_key ? product.order_key : "-"}</td>`);
      newRow.append(`<td>${product.orderProducts.length}</td>`);
      newRow.append(
        `<td>
      ` +
          product.orderProducts?.map((product) => {
            return `
            <div class="flex order-products">
               <img src=${product.product.product_main_image} />
               </div>`;
          }) +
          `
      </td>`
      );

      newRow.append(
        `<td>${
          product.order_date_created ? product.order_date_created : "-"
        }</td>`
      );
      newRow.append(
        `<td>${
          product.order_storee_status ? product.order_storee_status : "-"
        }</td>`
      );

      newRow.append(`<td>${product.phone}</td>`);

      newRow.append(`<td>${product.email}</td>`);
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
