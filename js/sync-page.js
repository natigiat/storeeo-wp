jQuery(document).ready(function ($) {
  $(".sync").on("click", ".btn", function () {
    const element = $(this);
    addProduct(element);
  });

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
    var productName = product.find(".name").text();
    var productPrice = product.find(".price").text();
    var productSku = product.find(".sku").text();
    var productImageSrc = product.find(".image img").attr("src");
    var product_quantity = product.find(".stock").text();
    var post_content = product.find(".post_content").text();
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
    };
    console.log({ data });

    const create_record = await createRecord("products", data);

    if (create_record.error) {
      $(".error").remove();
      element.append('<div class="error">Error upload Products</div>');
    } else {
      element.append(`<div class="success">Product Added</div>`);
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
