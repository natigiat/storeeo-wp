jQuery(document).ready(function ($) {
  $(".sync").on("click", ".share-product", function () {
    const element = $(this);
    $(".error").remove();

    const regular_price = Number(
      $(this).closest("tr").find(".regular-price").text()
    );
    const storeeo_price = Number(
      $(this).closest("tr").find(".store_price").val()
    );

    console.log({ storeeo_price });
    console.log({ regular_price });
    if (!storeeo_price) {
      $(this).after("<div class='error'>Please set suppliers price</div>");
      $(this).closest("tr").find(".store_price").focus();
    } else if (Number(regular_price) < storeeo_price) {
      $(this).after(
        "<div class='error'>Your regular price must be greater than suppliers price</div>"
      );
    } else {
      addProduct(element, storeeo_price);
    }
  });

  $("body").on("click", ".connected-product", function () {
    $(this).after(`
    <div class="disable-connected-product">
       <div class="item">Stop Sharing</div>
    </div>
  `);
  });

  $("body").on("click", ".disable-connected-product", function () {
    const element = $(this);
    const product_id = $(this).closest("tr").find(".product_id").text();
    const storeeo_sync_id = $(this)
      .closest("tr")
      .find(".storeeo-sync-id")
      .text();
    changeProductStatus(product_id, storeeo_sync_id, element, false);
  });

  async function changeProductStatus(
    product_id,
    storeeo_sync_id = false,
    element,
    status
  ) {
    $.ajax({
      type: "POST",
      url: ajax_call.change_product_sync_status,
      data: {
        product_id: product_id,
        storeeo_sync_id: storeeo_sync_id,
        status: status,
      },
      success: function (response) {
        const item = element.closest(".sync").find(".btn");
        if (response === "success") {
          item.removeClass("share-product");
          item.addClass("connected-product ,  btn-green");
          item.text("Connected");
        } else {
          item.removeClass("connected-product ,  btn-green");
          item.addClass("share-product");
          $(".disable-connected-product").remove();
          item.text("Share");
        }
      },
    });
  }

  // Function to simulate adding a product (you can implement your logic here)
  async function addProduct(element) {
    // Get the closest 'tr' element
    var product = element.closest("tr");
    var product_store_id = product.find(".product_id").text();

    if (!product) {
      return false;
    }

    $.ajax({
      type: "POST",
      url: ajax_call.add_product_to_storeeo,
      data: {
        product_store_id: product_store_id,
        storeeo_price: storeeo_price,
      },
      success: function (response) {
        const storeeo_sync_id = Number(response);
        changeProductStatus(product_store_id, storeeo_sync_id, element, true);
      },
    });
  }
});
