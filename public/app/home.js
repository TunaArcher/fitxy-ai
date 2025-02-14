document.addEventListener("DOMContentLoaded", function () {
    let target = (window.calPerDay - window.calToDay) < 0 ? 0 : window.calPerDay
    var g = document.getElementById("doughnutchart").getContext("2d");
    new Chart(g, {
      type: "doughnut",
      data: {
        labels: ["แคลอรี่วันนี้", "เป้าหมาย"],
        datasets: [
          {
            label: "fitness Categories",
            // data: [45, 30, 25, 10],
            // backgroundColor: ["#08a046", "#fc7a1e", "#03aed2", "#ffffff"],
            data: [window.calToDay, target],
            // data: [(window.calPerDay - 0), 0],
            backgroundColor: ["#03aed2", "#ffffff"],
            borderWidth: 0,
          },
        ],
      },
      options: {
        responsive: !0,
        cutout: 60,
        tooltips: { position: "nearest", yAlign: "bottom" },
        plugins: {
          legend: { display: !1, position: "top" },
          title: { display: !1, text: "Chart.js Doughnut Chart" },
        },
        layout: { padding: 0 },
      },
    });
  });
  