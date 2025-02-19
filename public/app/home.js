// document.addEventListener("DOMContentLoaded", function () {
//   // ดึงค่าจากตัวแปรที่ใช้ในระบบ
//   // let maintenance = window.maintenanceCal || 0; // ค่าที่ร่างกายต้องการเพื่อรักษาสมดุล
//   // let target = window.calPerDay || 0; // เป้าหมาย (Target) สำหรับเพิ่มน้ำหนัก
//   // let consumed = window.calToDay || 0; // แคลอรี่ที่บริโภค
//   // let burned = window.calBurn || 0; // แคลอรี่ที่เผาผลาญ

//   let maintenance = 2500
//   let target = 3000
//   let consumed = 4000
//   let burned = 1000

//   // กำหนดค่า baseValue เป็น Target
//   let baseValue = target;

//   // คำนวณค่าแคลอรี่ที่เหลือ
//   let calRemaining = baseValue - consumed;
//   if (calRemaining < 0) calRemaining = 0;

//   // ตรวจสอบว่าค่า consumed และ burned ไม่ติดลบ
//   if (consumed < 0) consumed = 0;
//   if (burned < 0) burned = 0;

//   // กำหนดค่า chartData สำหรับแสดงในกราฟ
//   let chartData = [consumed, calRemaining, burned];

//   // สร้าง Doughnut Chart
//   var ctx = document.getElementById("doughnutchart").getContext("2d");
//   new Chart(ctx, {
//     type: "doughnut",
//     data: {
//       labels: ["แคลอรี่ที่กินไป", "แคลอรี่ที่เหลือ", "เผาผลาญ"],
//       datasets: [
//         {
//           label: "แคลอรี่ที่ใช้ไป",
//           data: chartData,
//           backgroundColor: ["rgba(66, 135, 245, 0.7)", "#e0e0e0", "rgba(245, 66, 135, 0.7)"],
//           borderWidth: 0,
//         },
//       ],
//     },
//     options: {
//       responsive: true,
//       cutout: 60,
//       plugins: {
//         legend: { display: false },
//         title: { display: false },
//       },
//       layout: { padding: 0 },
//     },
//   });

//   // แสดงข้อมูลตัวเลขด้านล่างของกราฟ
//   const labelsContainer = document.getElementById("labels");
//   labelsContainer.innerHTML = `
//       <div>รักษาสมดุล (Maintenance): ${maintenance} แคล</div>
//       <div>เป้าหมาย (Target): ${target} แคล</div>
//       <div>บริโภค (Consumed): ${consumed} แคล</div>
//       <div>เผาผลาญ (Burned): ${burned} แคล</div>
//     `;
// });

function renderCircle() {
  const canvas = document.getElementById("calorieCanvas");
  const ctx = canvas.getContext("2d");

  // ใช้ขนาดจริงของ Canvas
  const centerX = canvas.width / 2;
  const centerY = canvas.height / 2;

  // กำหนดรัศมีให้สัมพันธ์กับขนาด Canvas
  const outerR = Math.min(canvas.width, canvas.height) / 2 - 10;
  const innerR = outerR - 30;

  // ล้างค่าเดิมก่อนวาดใหม่
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  let maintenance = window.maintenanceCal || 0;
  let target = window.calPerDay || 0;
  let consumed = window.calToDay || 0;
  let burned = window.calBurn || 0;

  const baseValue = target;
  const startAngle = -Math.PI / 2;

  function drawDonutSegment(innerR, outerR, start, end, color) {
    ctx.beginPath();
    ctx.arc(centerX, centerY, outerR, start, end, false);
    ctx.arc(centerX, centerY, innerR, end, start, true);
    ctx.closePath();
    ctx.fillStyle = color;
    ctx.fill();
  }

  drawDonutSegment(innerR, outerR, 0, 2 * Math.PI, "#e0e0e0");

  let totalConsumedAngle = (consumed / baseValue) * 2 * Math.PI;
  const fullRounds = Math.floor(totalConsumedAngle / (2 * Math.PI));
  let remainderAngle = totalConsumedAngle % (2 * Math.PI);
  if (remainderAngle === 0 && consumed > 0) {
    remainderAngle = 2 * Math.PI;
  }

  for (let i = 0; i < fullRounds; i++) {
    let roundStart = startAngle + i * 2 * Math.PI;
    let roundEnd = roundStart + 2 * Math.PI;
    drawDonutSegment(innerR, outerR, roundStart, roundEnd, "rgba(66, 135, 245, 0.7)");
  }

  let lastSegmentStart = startAngle + fullRounds * 2 * Math.PI;
  let lastSegmentEnd = lastSegmentStart + remainderAngle;
  drawDonutSegment(innerR, outerR, lastSegmentStart, lastSegmentEnd, "rgba(66, 135, 245, 0.7)");

  let burnedAngle = (burned / baseValue) * 2 * Math.PI;
  if (burnedAngle > 0) {
    let burnedStartAngle = lastSegmentEnd - burnedAngle;
    let burnedEndAngle = lastSegmentEnd;
    drawDonutSegment(innerR, outerR, burnedStartAngle, burnedEndAngle, "rgba(245, 66, 135, 0.7)");
  }
}


function renderProgress() {
  // กำหนดข้อมูลสำหรับกรณีเพิ่มน้ำหนัก
  // const maintenance = 2000; // รักษาสมดุล
  // const target = 2500; // เป้าหมายสำหรับเพิ่มน้ำหนัก
  // const consumed = 1550; // บริโภค
  // const burned = 0; // เผาผลาญ
  let maintenance = window.maintenanceCal || 0; // ค่าที่ร่างกายต้องการเพื่อรักษาสมดุล
  let target = window.calPerDay || 0; // เป้าหมาย (Target) สำหรับเพิ่มน้ำหนัก
  let consumed = window.calToDay || 0; // แคลอรี่ที่บริโภค
  let burned = window.calBurn || 0; // แคลอรี่ที่เผาผลาญ
  const netConsumption = consumed - burned; // 2800 - 200 = 2600

  // กำหนด baseValue เพื่อให้กราฟรองรับค่าที่มากที่สุด
  const baseValue = Math.max(consumed, target, maintenance); // 2800

  // คำนวณเปอร์เซ็นต์จาก baseValue
  const netPerc = (netConsumption / baseValue) * 100; // 2600/2800*100 ≈ 92.86%
  const burnedPerc = (burned / baseValue) * 100; // 200/2800*100 ≈ 7.14%
  const maintenancePerc = (maintenance / baseValue) * 100; // 2000/2800*100 ≈ 71.43%
  const targetPerc = (target / baseValue) * 100; // 2500/2800*100 ≈ 89.29%

  // กำหนดความกว้างของแท่งในกราฟ
  document.getElementById("netBar").style.width = netPerc + "%";
  document.getElementById("burnedBar").style.width = burnedPerc + "%";
  // วาง Marker ที่ตำแหน่งคำนวณได้
  document.getElementById("maintenanceMarker").style.left =
    maintenancePerc + "%";
  document.getElementById("targetMarker").style.left = targetPerc + "%";

  // แสดงข้อมูลตัวเลข
  const labelsContainer = document.getElementById("labels");
  labelsContainer.innerHTML = `
      <div class="col-3 text-center"><span>ทาน ${consumed}</span></div>
      <div class="col-3 text-center"><span>เป้าหมาย ${target}</span></div>
      <div class="col-3 text-center"><span>สมดุล ${maintenance}</span></div>
      <div class="col-3 text-center"><span>เผาผลาญ ${burned}</span></div>
    `;
}

renderCircle();
renderProgress();
