jQuery(document).ready(async function ($) {
  getOrders();

  // add product onClick
  $("body").on("click", ".pay-to-shop", function (e) {
    const item = $(this);
    const product_data = $(this).closest("tr").data("product");
    payToShop(item, product_data);
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
      newRow.append(`<td>${product.amount ? product.amount : "-"}</td>`);

      let totalAmount = 0;
      product.orderProducts.forEach((product) => {
        const price = parseFloat(product.product.product_storeeo_price);
        if (!isNaN(price)) {
          totalAmount += price;
        }
      });

      totalAmount =
        totalAmount !== 0 ? totalAmount.toFixed(2).replace(/^0+/, "") : "-";

      newRow.append(`<td>${totalAmount}</td>`);
      newRow.append(`<td class="profit">${product.amount - totalAmount}</td>`);
      newRow.append(`
      <td class="flex order-products">
        ${product.orderProducts
          ?.map(
            (product) => `
          <img src=${product.product.product_main_image} />
        `
          )
          .join("")}
      <div class="order-items-length">(${
        product.orderProducts.length
      } items)</div></td>`);

      newRow.append(
        `<td class="user_info link">${
          product.first_name + " " + product.last_name
        }</td>`
      );
      newRow.append(
        `<td><a class="link" href="tel:${product.phone}">${
          product.phone ? product.phone : "-"
        }</a></td>`
      );

      const options = {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        timeZone: "UTC",
      };

      const originalDate = new Date(product.createdAt);
      let formattedDate = originalDate.toLocaleString("en-GB", options);
      formattedDate = formattedDate.replace(",", " -");
      newRow.append(`<td>${formattedDate}</td>`);

      newRow.append(`<td> <button class=''>order created</button></td>`);
      newRow.append(
        `<td>  <button class='btn pay-to-shop'>pay the supplier</button></td>`
      );
      tableBody.append(newRow);
    });
  }

  async function payToShop(item, product) {
    console.log({ product });

    $.ajax({
      type: "POST",
      url: ajax_call.send_payment_to_supllier,
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
