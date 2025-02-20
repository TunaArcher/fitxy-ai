// JavaScript/jQuery
$(document).ready(function () {
  var selectedMenuId = null; // ตัวแปรเก็บ ID เมนูที่เลือก

  // เมื่อคลิกที่การ์ด (แต่ไม่ใช่ปุ่มปิด) ให้เปิด modal และตั้งค่า selectedMenuId
  $(".adminuiux-card").on("click", function (e) {
    if ($(e.target).hasClass("close-btn")) {
      return;
    }
    selectedMenuId = $(this).data("menu-id");
    // ดึงค่าแคลอรี่ปัจจุบันและลบ " แคลอรี่" กับ comma ออก
    var currentCal = $(this)
      .find(".menu-cal")
      .text()
      .replace(" kcal", "")
      .replace(/,/g, "")
      .trim();
    // ถ้ามีค่า ให้ฟอร์แมทด้วย comma ก่อนแสดงใน input
    if (currentCal !== "") {
      var formattedCal = parseInt(currentCal, 10).toLocaleString();
      $("#txtCal").val(formattedCal);
    } else {
      $("#txtCal").val("");
    }
  });

  // ฟังก์ชันคำนวณยอดแคลอรี่รวมใหม่จากการ์ดทั้งหมด
  function updateTotalCalToday() {
    var total = 0;
    $(".adminuiux-card").each(function () {
      var text = $(this)
        .find(".menu-cal")
        .text()
        .replace(" kcal", "")
        .replace(/,/g, "")
        .trim();
      var calVal = parseInt(text, 10);
      if (!isNaN(calVal)) {
        total += calVal;
      }
    });
    $("#totalCalToday").text(total.toLocaleString());
  }

  // จำกัด input ให้รับเฉพาะตัวเลขและฟอร์แมท comma อัตโนมัติ
  $("#txtCal").on("input", function () {
    // นำ comma ออกแล้วเอาเฉพาะตัวเลข
    var inputVal = $(this).val().replace(/,/g, "").replace(/\D/g, "");
    if (inputVal === "") {
      $(this).val("");
      return;
    }
    // ฟอร์แมทตัวเลขให้มี comma
    var formatted = parseInt(inputVal, 10).toLocaleString();
    $(this).val(formatted);
  });

  // เมื่อกดปุ่ม "อัปเดท" ใน modal
  $("#btnUpdate").on("click", function () {
    // ดึงค่าจาก input แล้วลบ comma ออก
    var newCalStr = $("#txtCal").val().trim().replace(/,/g, "");
    if (newCalStr === "" || isNaN(newCalStr)) {
      alert("กรุณากรอกจำนวนแคลอรี่ที่ถูกต้อง");
      return;
    }
    if (selectedMenuId == null) {
      alert("ไม่พบรายการที่เลือก");
      return;
    }

    // ส่ง AJAX เพื่ออัปเดตข้อมูล
    $.ajax({
      url: `${serverUrl}/menu/update`,
      type: "POST",
      data: JSON.stringify({
        menu_id: selectedMenuId,
        cal: newCalStr,
      }),
      contentType: "application/json; charset=utf-8",
      success: function (response) {
        if (response.success) {
          alert("อัปเดทข้อมูลสำเร็จ!");
          // อัปเดทข้อความใน h4 ของการ์ดที่เกี่ยวข้อง
          $(".adminuiux-card").each(function () {
            if ($(this).data("menu-id") == selectedMenuId) {
              $(this)
                .find(".menu-cal")
                .text(parseInt(newCalStr, 10).toLocaleString() + " kcal");
            }
          });
          // คำนวณยอดแคลอรี่รวมใหม่
          updateTotalCalToday();
          // ปิด modal
          $("#standardmodal").modal("hide");
        } else {
          alert("เกิดข้อผิดพลาด: " + response);
        }
      },
      error: function () {
        alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับ server");
      },
    });
  });

  // เมื่อกดปุ่ม X (close-btn) ให้ลบรายการนั้น
  $(".close-btn").on("click", function (e) {
    e.stopPropagation(); // ป้องกัน event bubble ไปที่การ์ด
    var $card = $(this).closest(".adminuiux-card");
    var menuId = $card.data("menu-id");

    if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?")) {
      $.ajax({
        url: `${serverUrl}/menu/delete`,
        type: "POST",
        data: JSON.stringify({ menu_id: menuId }),
        contentType: "application/json; charset=utf-8",
        success: function (response) {
          if (response.success) {
            alert("ลบรายการสำเร็จ!");
            // ลบการ์ดออกจาก DOM พร้อม fade out แล้วคำนวณยอดรวมใหม่
            $card.closest(".col-12").fadeOut(500, function () {
              $(this).remove();
              updateTotalCalToday();
            });
          } else {
            alert("เกิดข้อผิดพลาด: " + response);
          }
        },
        error: function () {
          alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับ server");
        },
      });
    }
  });
});
