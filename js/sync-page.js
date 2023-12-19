jQuery(document).ready(function ($) {
  $(".sync").on("click", ".share-product", function () {
    const element = $(this);
    addProduct(element);
  });

  $(".connected-product").on("click", function () {
    $(this).after(`
    <div class="disable-connected-product">
       <div class="item">Stop Sharing</div>
    </div>
  `);
  });

  $("body").on("click", ".disable-connected-product", function () {
    const element = $(this);
    const product_id = $(this).closest("tr").find(".product_id").text();
    changeProductStatus(product_id, element, false);
  });

  async function changeProductStatus(product_id, element, status) {
    $.ajax({
      type: "POST",
      url: ajax_call.change_product_sync_status,
      data: {
        product_id: product_id,
        status: status,
      },
      success: function (response) {
        $(".success").remove();
        element.after(`<div class="success">Product Added To Your Store</div>`);
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
    //   changeProductStatus(product_store_id, element, true);

    $.ajax({
      type: "POST",
      url: ajax_call.add_product_to_storeeo,
      data: {
        product_store_id: product_store_id,
      },
      success: function (response) {
        $(".success").remove();
        item.after(`<div class="success">Product Added To Your Store</div>`);
      },
    });
  }
});
