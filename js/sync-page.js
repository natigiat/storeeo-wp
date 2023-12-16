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
    var product_id = $(this).closest("tr").find(".product_id").text();
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
    const product_data = $(this).closest("tr").data("product");

    if (!product) {
      return false;
    }

    var shop_logo = $(".shop_logo").data("logo");

    // Get the text content using jQuery

    var product_store_id = product.find(".product_id").text();
    var productName = product.find(".name").text();
    var productPrice = product.find(".price").text();
    var productPrice = product.find(".price").text();
    var productSku = product.find(".sku").text();
    var productImageSrc = product.find(".image img").attr("src");
    var product_quantity = product.find(".stock").text();
    var post_content = product.find(".post_content").text();
    var product_regular_price = product.find(".regular-price").text();
    var product_storeeo_price = product.find(".storeeo-price").text();

    // Prepare the data
    var data = {
      product_title: productName,
      product_content: post_content,
      product_visibility: true,
      product_price: Number(productPrice),
      product_quantity: Number(product_quantity) ? Number(product_quantity) : 0,
      product_sku: productSku,
      product_barcode: "barcode",
      product_main_image: productImageSrc,
      shop_logo: shop_logo,
      product_store_id: Number(product_store_id),
      product_regular_price: Number(product_regular_price),
      product_storeeo_price: Number(product_storeeo_price),
    };

    const create_record = await createRecord("products", data);

    if (create_record.error) {
      $(".error").remove();
      element.append('<div class="error">Error upload Products</div>');
    } else {
      changeProductStatus(product_store_id, element, true);
    }

    // $.ajax({
    //   url: url,
    //   type: "POST",
    //   contentType: "application/json",
    //   data: JSON.stringify(data),
    //   success: function (response) {
    //     console.log(response);
    //   },
    //   error: function (xhr, status, error) {
    //     console.error(error);
    //   },
    // });
  }
});
