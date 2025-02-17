var foodTable;

$(document).ready(function () {
  // ตรวจสอบวันในสัปดาห์ (0 = Sunday, 1 = Monday, ...)
  var d = new Date();
  var days = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];
  var today = days[d.getDay()]; // เช่น "wed"

  // เมื่อคลิกที่วันใน swiper slide
  $(".dateselect .swiper-slide").on("click", function () {
    // เปลี่ยนสถานะ active ให้กับวันที่คลิก
    $(".dateselect .swiper-slide").removeClass("active");
    $(this).addClass("active");

    // ดึงชื่อวันจากข้อความใน <p> เช่น "Sun." แล้วทำให้อยู่ในรูปแบบ key (sun, mon, ...)
    var day = $(this).find("p").text().trim().replace(".", "").toLowerCase();

    // ซ่อนเมนูทั้งหมดก่อน
    $(".card-body.height-dynamic").hide();

    // แสดงเฉพาะเมนูที่ตรงกับวันที่คลิก (สมมุติว่า attribute data-day ตรงกัน)
    $('.card-body[data-day="' + day + '"]').show();
  });

  // ลบคลาส active จาก swiper slide ทั้งหมด แล้วเพิ่มให้กับ slide ที่ตรงกับวันนี้
  $(".dateselect .swiper-slide").each(function () {
    var slideDay = $(this)
      .find("p")
      .text()
      .trim()
      .replace(".", "")
      .toLowerCase();
    if (slideDay === today) {
      $(this).addClass("active");
    } else {
      $(this).removeClass("active");
    }
  });

  // ซ่อน container ของทุกวัน แล้วแสดงเฉพาะ container ที่ data-day ตรงกับวันนี้
  $(".card-body.height-dynamic").hide();
  $('.card-body[data-day="' + today + '"]').show();
});

// jQuery AJAX Script
$(document).ready(function () {
  $("#btnGenerateFood").on("click", function () {
    // ดึงค่าจาก input
    var query = $(".input-group input.form-control").val();

    const overlay = document.getElementById("processingOverlay");
    overlay.style.display = "flex";

    $.ajax({
      url: `${window.serverUrl}/food/generate`,
      type: "POST",
      dataType: "json",
      data: JSON.stringify({
        query: query,
      }),
      contentType: "application/json",
      success: function (response) {
        overlay.style.display = "none";
        // overlay.innerHTML = '<div class="spinner" style="display:none;"></div>';

        $("#btnSaveFood").show();
        let $data = response.data;
        foodTable = $data;

        // response คาดว่าจะเป็น object ที่มี key เป็นวัน เช่น "sun", "mon", ...
        $.each($data, function (day, meals) {
          // console.log(day)
          // หา element ที่มี data-day ตรงกับ key ของ json
          var $dayContainer = $(
            '.card-body.height-dynamic[data-day="' + day + '"]'
          );
          //   console.log($dayContainer)
          if ($dayContainer.length) {
            var html = "";

            // สร้าง HTML สำหรับมื้อเช้า
            if (meals.breakfast) {
              var bf = meals.breakfast;
              html +=
                '<div class="card mb-2">' +
                '<div class="card-body">' +
                '<p class="mb-3 small fw-medium text-secondary">มื้อเช้า <span class="text-warning bi bi-tag"></span></p>' +
                '<div class="row align-items-center gx-2 mb-0">' +
                '<div class="col-auto">' +
                '<img src="' +
                bf.url +
                '" class="avatar avatar-40 rounded" alt="" />' +
                "</div>" +
                '<div class="col">' +
                '<h6 class="mb-0">' +
                bf.menu_name +
                "</h6>" +
                '<p class="text-secondary small text-truncated"><i class="bi bi-fire me-1"></i>' + bf.cal + ' พลังงาน</p>' +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            }

            // สร้าง HTML สำหรับมื้อเที่ยง
            if (meals.lunch) {
              var ln = meals.lunch;
              html +=
                '<div class="card mb-2">' +
                '<div class="card-body">' +
                '<p class="mb-3 small fw-medium text-secondary">มื้อเที่ยง <span class="text-warning bi bi-tag"></span></p>' +
                '<div class="row gx-2 mb-0">' +
                '<div class="col-auto">' +
                '<img src="' +
                ln.url +
                '" class="avatar avatar-40 rounded" alt="" />' +
                "</div>" +
                '<div class="col">' +
                '<h6 class="mb-0">' +
                ln.menu_name +
                "</h6>" +
                '<p class="text-secondary small text-truncated"><i class="bi bi-fire me-1"></i>' + ln.cal + ' พลังงาน</p>' +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            }

            // สร้าง HTML สำหรับมื้อเย็น
            if (meals.dinner) {
              var dn = meals.dinner;
              html +=
                '<div class="card mb-2">' +
                '<div class="card-body">' +
                '<p class="mb-3 small fw-medium text-secondary">มื้อเย็น <span class="text-warning bi bi-tag"></span></p>' +
                '<div class="row gx-2 mb-0">' +
                '<div class="col-auto">' +
                '<img src="' +
                dn.url +
                '" class="avatar avatar-40 rounded" alt="" />' +
                "</div>" +
                '<div class="col">' +
                '<h6 class="mb-0">' +
                dn.menu_name +
                "</h6>" +
                '<p class="text-secondary small text-truncated"><i class="bi bi-fire me-1"></i>' + dn.cal + ' พลังงาน</p>' +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            }

            // สร้าง HTML สำหรับมื้อเย็น
            if (meals.snack) {
              var sn = meals.snack;
              html +=
                '<div class="card mb-2">' +
                '<div class="card-body">' +
                '<p class="mb-3 small fw-medium text-secondary">อาหารว่าง <span class="text-warning bi bi-tag"></span></p>' +
                '<div class="row gx-2 mb-0">' +
                '<div class="col-auto">' +
                '<img src="' +
                sn.url +
                '" class="avatar avatar-40 rounded" alt="" />' +
                "</div>" +
                '<div class="col">' +
                '<h6 class="mb-0">' +
                sn.menu_name +
                "</h6>" +
                '<p class="text-secondary small text-truncated"><i class="bi bi-fire me-1"></i>' + sn.cal + ' พลังงาน</p>' +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            }

            // อัพเดท HTML ใน container ของวันนั้น
            $dayContainer.html(html);
          }
        });
      },
      error: function (xhr, status, error) {
        console.error("เกิดข้อผิดพลาด: " + error);
      },
    });
  });

  $("#btnSaveFood").on("click", function () {
    const overlay = document.getElementById("processingOverlay");
    overlay.style.display = "flex";

    $.ajax({
      url: `${window.serverUrl}/food/saveTable`, // เปลี่ยน URL ให้ถูกต้อง
      type: "POST",
      dataType: "json",
      data: JSON.stringify({ foodTable }),
      contentType: "application/json",
      success: function (response) {
        overlay.style.display = "none";
        // overlay.innerHTML = '<div class="spinner" style="display:none;"></div>';

        location.href = `${window.serverUrl}/food/table`;
      },
      error: function (xhr, status, error) {
        console.error("เกิดข้อผิดพลาด: " + error);
      },
    });
  });
});
