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
    // open in
    // map products and inside map orderProducts and inside products object push product_store_id to array index

    console.log({ product });
    let shopUrls = {};
    let hasMultipleShops = false;

    product.orderProducts.forEach(function (orderProduct) {
      const url = orderProduct.product.shop.shop_url;
      const product_store_id = orderProduct.product.product_store_id;

      if (shopUrls[url]) {
        shopUrls[url].push(product_store_id);
        hasMultipleShops = true;
      } else {
        shopUrls[url] = [product_store_id];
      }
    });

    console.log({ shopUrls });
    if (hasMultipleShops) {
      $("body").prepend(`
        <div class="popover-back"></div>
        <div class="popover popover-2">
          <div class="popover-header">
            <div class="title">Pay to supplier - Enjoy your fee</div>
            <div class="popover-close"> x </div>
          </div>
          <div class="description">You will pay your costume price <br /> and supplier will take care of this order </div>
          
          <div class="shop-buttons">
            ${Object.keys(shopUrls)
              .map(
                (url, index) => `
              <button class="shop-button" data-url="${url}" data-index="${index}">Shop ${
                  index + 1
                }</button>
            `
              )
              .join("")}
          </div>
          
          <iframe id="shop-iframe" width="100%" height="100%"></iframe>
        </div>
      `);

      $(".shop-button").on("click", function () {
        const selectedUrl = $(this).data("url");
        const selectedIndex = $(this).data("index");
        const productIds = shopUrls[selectedUrl];
        console.log({ selectedUrl });

        // <iframe src="http://localhost/shop2/cart/?storeeo_checkout=true&pid=${product_store_id}" width="100%" height="100%"></iframe>

        // Update iframe source with the selected shop URL and product IDs
        $("#shop-iframe").attr(
          "src",
          `${selectedUrl}/cart/?storeeo_checkout=true&pid=[${productIds.join(
            ","
          )}]`
        );
      });
    } else {
      const url = Object.keys(shopUrls)[0];
      const productIds = shopUrls[url];
      console.log({ shopUrls });
      $("body").prepend(`
        <div class="popover-back"></div>
        <div class="popover popover-2">
          <div class="popover-header">
            <div class="title">Pay to supplier - Enjoy your fee</div>
            <div class="popover-close"> x </div>
          </div>
          <div class="description">You will pay your costume price <br /> and supplier will take care of this order </div>
          
          <iframe src="${url}/cart/?storeeo_checkout=true&pid=[${productIds.join(
        ","
      )}]" width="100%" height="100%"></iframe>
        </div>
      `);
    }

    $("body").on("click", ".popover-close", function () {
      $(".popover").remove();
      $(".popover-back").remove();
    });
  }
});
