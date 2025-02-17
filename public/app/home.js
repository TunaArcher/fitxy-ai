document.addEventListener("DOMContentLoaded", function () {
  // ดึงค่าจากตัวแปรที่ใช้ในระบบ
  let calPerDay = window.calPerDay || 0; // ค่าแคลอรี่ที่สามารถรับได้ต่อวัน (ถ้าไม่มีให้ใช้ค่า default)
  let calToDay = window.calToDay || 0; // ค่าแคลอรี่ที่ได้รับวันนี้
  let calBurn = window.calBurn || 0; // ค่าแคลอรี่ที่เผาผลาญไปแล้ว
  console.log(calPerDay)
  console.log(calToDay)
  console.log(calBurn)

  // คำนวณข้อมูลให้ได้ค่าที่ถูกต้อง
  let calConsumed = calToDay - calBurn; // แคลอรี่ที่กินไปจริง (หลังหักเผาผลาญ)
  let calRemaining = calPerDay - calConsumed; // แคลอรี่ที่สามารถกินได้อีก

  // ถ้าค่าแคลอรี่เหลือ < 0 ให้เป็น 0
  if (calRemaining < 0) calRemaining = 0;

  // ตรวจสอบว่าแคลอรี่ที่กินไปไม่ติดลบ
  if (calConsumed < 0) calConsumed = 0;

  // กำหนดค่า target สำหรับแสดงในกราฟ
  let chartData = [calConsumed, calRemaining, calBurn];

  console.log(chartData)

  // สร้าง Doughnut Chart
  var ctx = document.getElementById("doughnutchart").getContext("2d");
  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: ["แคลอรี่ที่กินไป", "แคลอรี่ที่เหลือ", "เผาผลาญ"],
      datasets: [
        {
          label: "แคลอรี่ที่ใช้ไป",
          data: chartData,
          backgroundColor: ["#03aed2", "#ffffff", "#fc7a1e"], // สีที่ใช้แสดงข้อมูล
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