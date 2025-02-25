const today = moment().format("DD/MM/YYYY");
var selectedDate = today;

$(document).ready(function () {
  function fetchData(selectedDate) {
    $.ajax({
      url: `${window.serverUrl}/summary/byDate`,
      type: "POST", // หรือ 'POST' ขึ้นอยู่กับการตั้งค่าของ API
      data: JSON.stringify({ date: selectedDate }),
      contentType: "application/json",
      success: function (response) {
        const data = response.data;

        console.log(data);

        // ----- Macro Nutrients (การทานแคลอรี่) -----
        const caloriesToday = parseFloat(data.menuMacronutrientsToday.calories);
        const calPerDay = parseFloat(data.cal_per_day) || 1; // หลีกเลี่ยงหารด้วย 0
        let progressPercent = (caloriesToday / calPerDay) * 100;
        if (progressPercent > 100) progressPercent = 100; // จำกัดค่าสูงสุดไม่เกิน 100%
        $("#cal_per_day").text("การทานแคลอรี่ " + calPerDay);

        // อัปเดต progress bar
        $(".progress-bar")
          .css("width", progressPercent + "%")
          .attr("aria-valuenow", progressPercent);
        $(".small.opacity-75").text("ทานไป " + caloriesToday.toFixed(0) + "");

        // อัปเดตค่า Macronutrients ใน card Carbs และ Protein
        // (ตรวจสอบให้ใช้ selector ที่แตกต่างกันเพราะใน HTML มีการใช้ id ซ้ำกัน)
        $("#carbsValue").text(
          parseFloat(data.menuMacronutrientsToday.carbs).toFixed(0)
        );
        $("#proteinValue").text(
          parseFloat(data.menuMacronutrientsToday.protein).toFixed(0)
        );
        // หากต้องการอัปเดตค่า fat ก็สามารถทำได้เช่นกัน

        // ----- Goal Stats (กราฟ 7 วัน) -----
        const menuCalories7days = data.menuCalories7days;
        // ดึง canvas context
        const ctx = document.getElementById("summarychart").getContext("2d");

        // สร้าง Linear Gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, 180);
        gradient.addColorStop(0, "rgba(30, 97, 252, 0.8)");
        gradient.addColorStop(1, "rgba(30, 97, 252, 0)");

        // เตรียมข้อมูลสำหรับกราฟ
        const labels = menuCalories7days.map((item) => {
          const parts = item.record_date.split("-"); // parts[0]: year, parts[1]: month, parts[2]: day
          return parts[2] + "/" + parts[1]; // แสดงเป็น "วัน/เดือน"
        });
        const caloriesData = menuCalories7days.map((item) =>
          parseFloat(item.calories_today)
        );

        // กำหนดค่า config สำหรับ Chart.js พร้อม annotation สองเส้นที่ค่า 2000 และ 3000
        const chartConfig = {
          type: "line",
          data: {
            labels: labels,
            datasets: [
              {
                label: "Calories Today",
                data: caloriesData,
                radius: 0,
                backgroundColor: gradient,
                borderColor: "rgba(30, 97, 252, 1)",
                borderWidth: 1,
                fill: true,
                tension: 0,
              },
            ],
          },
          options: {
            animation: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              annotation: {
                annotations: {
                  line2000: {
                    type: "line",
                    yMin: data.maintenanceCal,
                    yMax: data.maintenanceCal,
                    borderColor: "rgba(0, 0, 0, 0.5)",
                    borderDash: [6, 6],
                    borderWidth: 2,
                    label: {
                      enabled: true,
                      content: "สมดุล",
                      position: "end",
                      backgroundColor: "rgba(0, 0, 0, 0.7)",
                      color: "#fff",
                      font: {
                        style: "normal",
                        weight: "bold",
                      },
                    },
                  },
                  line3000: {
                    type: "line",
                    yMin: data.cal_per_day,
                    yMax: data.cal_per_day,
                    borderColor: "rgba(0, 0, 0, 0.5)",
                    borderDash: [6, 6],
                    borderWidth: 2,
                    label: {
                      enabled: true,
                      content: "Limit",
                      position: "end",
                      backgroundColor: "rgba(0, 0, 0, 0.7)",
                      color: "#fff",
                      font: {
                        style: "normal",
                        weight: "bold",
                      },
                    },
                  },
                },
              },
            },
            scales: {
              y: { display: false, beginAtZero: true },
              x: { grid: { display: false }, display: true },
            },
          },
        };

        // สร้างกราฟ
        // ตรวจสอบว่ามี Chart instance อยู่แล้วหรือไม่
        if (window.myChart) {
          window.myChart.destroy();
        }

        // สร้าง Chart ใหม่และเก็บไว้ในตัวแปร global
        window.myChart = new Chart(ctx, chartConfig);

        // ----- วิเคราะห์ -----
        const textData = data.analyze;
        if  (data.analyze == '') {
          $("#wrapperAnalyze").hide()
          $("#analyze").html(marked.parse(textData));
        } else {
          $("#wrapperAnalyze").show()
          $("#analyze").html(marked.parse(textData));
        }
        

        // ----- Workout Summary -----
        // สมมุติมี element ที่มี id สำหรับเวลาและแคลอรี่ที่เผาผลาญ
        $("#workoutSummaryTodayTime").html(
          data.workoutSummaryToday.time + " นาที"
        );
        $("#workoutSummaryTodayCalories").html(
          data.workoutSummaryToday.calories + " kcal"
        );
        // สำหรับข้อมูล Step count หรือ Running distance หากมี ควรเพิ่มเข้าไปตาม element ที่สร้างไว้

        // ----- ผลการวิเคราะห์ -----
        // หากมีข้อมูลใน analyze ให้นำมาแสดง
        if (data.analyze && data.analyze.length > 0) {
          $("#analyzeText").html(data.analyze.join("<br>"));
        } else {
          $("#analyzeText").text("ยังไม่มีข้อมูลผลการวิเคราะห์");
        }
      },
      error: function (xhr, status, error) {
        console.error("เกิดข้อผิดพลาดในการดึงข้อมูล: ", error);
      },
    });
  }

  $("#datepickers").daterangepicker(
    {
      singleDatePicker: true, // เลือกได้แค่วันเดียว
      minYear: 2025,
      autoApply: true, // ไม่ต้องกดปุ่ม Apply
      linkedCalendars: false, // ไม่ให้ปฏิทินซิงค์กัน
      alwaysShowCalendars: true, // แสดงปฏิทินเสมอ
      startDate: moment().format("DD/MM/YYYY"), // ใช้ moment.js กำหนดให้เป็นวันปัจจุบัน
      opens: "center", // เปิดปฏิทินตรงกลาง
      drops: "auto", // ให้ dropdown ของเดือน/ปี ปรับตำแหน่งอัตโนมัติ
      buttonClasses: "btn", // คลาสของปุ่ม
      applyButtonClasses: "btn-theme", // คลาสของปุ่ม Apply
      cancelClass: "btn-light", // คลาสของปุ่ม Cancel
      locale: {
        format: "DD/MM/YYYY", // รูปแบบของวันที่
      },
    },
    function (start, end, label) {
      selectedDate = start.format("DD/MM/YYYY");
      fetchData(selectedDate);
    }
  );

  fetchData(today);

  // เมื่อ modal เปิด ให้ยิง AJAX เพื่อดึงข้อมูลการออกกำลังกาย
  $("#workoutModal").on("show.bs.modal", function () {
    // กำหนดตัวแปรสำหรับ modal-body
    const $modalBody = $(this).find(".modal-body");
    // เคลียร์เนื้อหาเดิม (ถ้ามี)
    $modalBody.empty();

    $.ajax({
      url: `${window.serverUrl}/workout/data`,
      type: "POST",
      data: JSON.stringify({ date: selectedDate }),
      contentType: "application/json",
      success: function (response) {
        // สมมุติว่า response.data เป็น array ของ workout object
        // ตัวอย่าง object: { id, name, duration, calories, image }
        if (
          response &&
          response.data &&
          Array.isArray(response.data) &&
          response.data.length
        ) {
          response.data.forEach(function (workout) {
            // สร้าง markup สำหรับ card แต่ละอัน
            const cardMarkup = `
            <div class="col-12 mb-3">
              <div class="card adminuiux-card border-0 bg-gradient-1 mt-3" data-workout-id="${workout.id}">
                <div class="card-body position-relative z-index-1">
                  <div class="row">
                    <div class="col-3">
                      <figure class="height-70 w-100 rounded coverimg coverimg-x mb-0" style="background-image: url('${serverUrl}/assets/img/workout/${workout.icon}');">
                        <img src="${serverUrl}/assets/img/workout/${workout.icon}" alt="" style="display: none;">
                      </figure>
                    </div>
                    <div class="col-9">
                      <h6 class="text-truncated">${workout.user_workout_title}</h6>
                      <p class="text-secondary fs-14 mb-2">
                        <span class="me-1"><i class="bi bi-clock me-1"></i> ${workout.time}</span>
                        <span class="me-1"><i class="bi bi-fire me-1"></i> ${workout.calories} แคลอรี่</span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `;
            // เพิ่ม card เข้าไปใน modal-body
            $modalBody.append(cardMarkup);
          });
        } else {
          // หากไม่มีข้อมูล ให้แสดงข้อความแจ้งเตือน
          $modalBody.append(
            '<p class="text-center">ไม่พบข้อมูลการออกกำลังกาย</p>'
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("เกิดข้อผิดพลาดในการดึงข้อมูลการออกกำลังกาย: ", error);
        $modalBody.append(
          '<p class="text-center text-danger">เกิดข้อผิดพลาดในการดึงข้อมูล</p>'
        );
      },
    });
  });

  $("#menuModal").on("show.bs.modal", function () {
    // ดึง element ของ modal-body และเคลียร์เนื้อหาเดิม
    const $modalBody = $(this).find(".modal-body");
    $modalBody.empty();

    $.ajax({
      url: `${window.serverUrl}/menu/data`,
      type: "POST",
      data: JSON.stringify({ date: selectedDate }),
      contentType: "application/json",
      success: function (response) {
        // สมมุติว่า response.data เป็น array ของเมนูที่มี property เช่น:
        // { id, name, content, carbohydrates, protein, fat, calories }
        if (
          response &&
          response.data &&
          Array.isArray(response.data) &&
          response.data.length
        ) {
          response.data.forEach(function (menu) {
            const cardMarkup = `
              <div class="col-12 mb-3">
                <div class="card adminuiux-card" data-menu-id="${menu.id}">
                  <div class="card-body pt-2">
                    <div class="row gx-3 align-items-center mb-2" data-bs-toggle="modal" data-bs-target="#standardmodal">
                      <div class="col">
                        <h6 class="text-truncated">${menu.name}</h6>
                      </div>
                    </div>
                    <div class="row gx-3 align-items-center" data-bs-toggle="modal" data-bs-target="#standardmodal">
                      <div class="col-4">
                        <figure class="height-50 w-100 rounded coverimg mb-0" style="background-image: url('${
                          menu.content
                        }');">
                          <img src="${
                            menu.content
                          }" alt="" style="display: none;">
                        </figure>
                      </div>
                      <div class="col-8">
                        <div class="row gx-3">
                          <div class="col">
                            <p class="small mb-0">${parseFloat(
                              menu.carbohydrates
                            ).toFixed(0)} g</p>
                            <p class="fs-12 opacity-75">Carbs</p>
                          </div>
                          <div class="col">
                            <p class="small mb-0">${parseFloat(
                              menu.protein
                            ).toFixed(0)} g</p>
                            <p class="fs-12 opacity-75">Protein</p>
                          </div>
                          <div class="col">
                            <p class="small mb-0">${parseFloat(
                              menu.fat
                            ).toFixed(0)} g</p>
                            <p class="fs-12 opacity-75">Fat</p>
                          </div>
                          <div class="col">
                            <p class="small mb-0 menu-cal">${parseFloat(
                              menu.calories
                            ).toFixed(0)} kcal</p>
                            <p class="fs-12 opacity-75">Energy</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            `;
            $modalBody.append(cardMarkup);
          });
        } else {
          // กรณีไม่มีข้อมูล
          $modalBody.append(
            '<p class="text-center">ไม่พบข้อมูลรายการการกิน</p>'
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("เกิดข้อผิดพลาดในการดึงข้อมูลการกิน: ", error);
        $modalBody.append(
          '<p class="text-center text-danger">เกิดข้อผิดพลาดในการดึงข้อมูล</p>'
        );
      },
    });
  });
});
