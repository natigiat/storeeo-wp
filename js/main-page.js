jQuery(document).ready(function ($) {
  //general sales
  var data = {
    labels: [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
      "Sunday",
    ],
    datasets: [
      {
        label: "Sales Data by Days",
        data: [200, 300, 400, 600, 700, 500, 300], // Sales data for each day
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 1,
      },
    ],
  };

  var ctx = document.getElementById("myChart").getContext("2d");

  var myChart = new Chart(ctx, {
    type: "line",
    data: data,
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });

  // total sales
  var total_data = {
    labels: ["Shop 1", "Shop 2", "Shop 3", "Shop 4", "Shop 5"],
    datasets: [
      {
        label: "Shop Data",
        data: [20, 30, 40, 50, 60], // Example data for each shop
        backgroundColor: [
          "rgba(255, 99, 132, 0.5)",
          "rgba(54, 162, 235, 0.5)",
          "rgba(255, 206, 86, 0.5)",
          "rgba(75, 192, 192, 0.5)",
          "rgba(153, 102, 255, 0.5)",
        ],
        borderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
        ],
        borderWidth: 1,
      },
    ],
  };

  // Get the canvas element
  var ctx_total = document.getElementById("totalSales").getContext("2d");

  // Render Chart using Chart.js
  var total_chaert = new Chart(ctx_total, {
    type: "doughnut",
    data: total_data,
    options: {
      plugins: {
        legend: {
          display: true,
          position: "right",
        },
      },
      responsive: true,
      maintainAspectRatio: false,
    },
  });

  // yourProfit
  var data_yourProfit = {
    datasets: [
      {
        label: "Shop Data",
        data: [20], // Example data for each shop
        backgroundColor: [
          "rgba(255, 99, 132, 0.5)",
          "rgba(54, 162, 235, 0.5)",
          "rgba(255, 206, 86, 0.5)",
          "rgba(75, 192, 192, 0.5)",
          "rgba(153, 102, 255, 0.5)",
        ],
        borderColor: [
          "rgba(255, 99, 132, 1)",
          "rgba(54, 162, 235, 1)",
          "rgba(255, 206, 86, 1)",
          "rgba(75, 192, 192, 1)",
          "rgba(153, 102, 255, 1)",
        ],
        borderWidth: 1,
      },
    ],
  };

  // Get the canvas element
  var ctx_yourProfit = document.getElementById("yourProfit").getContext("2d");

  // Render Chart using Chart.js
  var total_yourProfit = new Chart(ctx_yourProfit, {
    type: "doughnut",
    data: data_yourProfit,
    options: {
      plugins: {
        legend: {
          display: true,
          position: "right",
        },
      },
      responsive: true,
      maintainAspectRatio: false,
    },
  });

  // get orders
  getOrders();
  async function getOrders() {
    const allOrders = await getRecord("orders");
    const tableBody = $("#the-list");

    console.log({ allOrders });
    allOrders.forEach((product) => {
      const product_data = JSON.stringify(product);
      const newRow = $(`<tr data-product='${product_data}'>`);

      newRow.append(`<td>${product.order_key ? product.order_key : "-"}</td>`);
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

      tableBody.append(newRow);
    });
  }
});
