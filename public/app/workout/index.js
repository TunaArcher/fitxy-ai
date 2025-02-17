// โหลด jQuery ก่อน จากนั้นโหลด Bootstrap JS (แนะนำให้ใส่สคริปต์ด้านล่างก่อนปิด </body>)
$(document).ready(function () {
  let currentMet = 0; // เก็บค่า MET ของ workout ปัจจุบัน

  // ฟังก์ชันคำนวณแคลอรี่ (สูตร: MET × Weight(kg) × Time(minutes) × 0.0175)
  function calculateCalories() {
    let weight = parseFloat(window.userWeight) || 0;
    let time = parseFloat($("#input-time").val()) || 0;
    let calories = currentMet * weight * time * 0.0175;
    $("#result-calories").text(calories.toFixed(2));
  }

  function updateTotalCalToday() {
    var total = 0;
    $(".adminuiux-card").each(function () {
      // ค้นหา <i> ที่มี class "bi-fire" แล้วดึง <span> ที่เป็น parent
      var calText = $(this).find("i.bi-fire").parent().text();
      // ตัวอย่าง calText อาจได้ " 259.88 แคลอรี่"
      // กรองเอาเฉพาะตัวเลขและจุดทศนิยม
      var calVal = parseFloat(calText.replace(/[^0-9.]/g, ""));
      if (!isNaN(calVal)) {
        total += calVal;
      }
    });
    $("#totalCalToday").text(total.toLocaleString());
  }

  // เมื่อคลิกเลือก Workout ให้เปิด Modal และเซตข้อมูลใน Modal
  $(".open-workout-modal").on("click", function (e) {
    e.preventDefault();

    let id = $(this).data("workout-id");
    let title = $(this).data("workout-title");
    let icon = $(this).data("workout-icon");
    let met = $(this).data("workout-met");

    // อัปเดตข้อมูลใน Modal
    $("#modal-workout-id").val(id);
    $("#modal-workout-title").text(title);
    $("#modal-workout-bg").css("background-image", "url(" + icon + ")");
    $("#modal-workout-avatar").css("background-image", "url(" + icon + ")");
    $("#workout-example-calculate").text(
      `(MET: ${met}) x ${window.userWeight} x นาที x 0.0175`
    );

    currentMet = parseFloat(met) || 0;

    // รีเซ็ตค่าใน input และผลลัพธ์
    $("#input-time").val("");
    $("#result-calories").text("0");
  });

  // คำนวณแคลอรี่เมื่อเปลี่ยนค่าในช่อง input ระยะเวลา
  $("#input-time").on("input", calculateCalories);

  // เมื่อกดปุ่ม "บันทึก" ส่งข้อมูลไปที่ server ผ่าน AJAX
  // $("#btn-save").on("click", function () {
  //   let $me = $(this);

  //   // เก็บข้อมูลที่ต้องการส่ง
  //   let workoutID = $("#modal-workout-id").val();
  //   let workoutTitle = $("#modal-workout-title").text();
  //   //   let workoutMet = currentMet;
  //   let workoutTime = $("#input-time").val();
  //   let calculatedCalories = $("#result-calories").text();
  //   //   let userWeight = window.userWeight; // สมมติว่ามีการกำหนดไว้แล้ว

  //   // ดึง URL รูปจาก background-image
  //   let bgImage = $("#modal-workout-bg").css("background-image");
  //   // ตัวอย่าง bgImage จะอยู่ในรูปแบบ: url("http://example.com/path/image.jpg")
  //   // เราแปลงให้เป็น URL ที่เรียบง่าย
  //   bgImage = bgImage.replace(/^url\(["']?/, "").replace(/["']?\)$/, "");

  //   // จัดข้อมูลที่จะส่งไปในตัวแปร postData
  //   let postData = {
  //     id: workoutID,
  //     title: workoutTitle,
  //     // met: workoutMet,
  //     time: workoutTime,
  //     // weight: userWeight,
  //     calories: calculatedCalories,
  //     // background_image: bgImage
  //   };

  //   const overlay = document.getElementById("processingOverlay");
  //   overlay.style.display = "flex";

  //   // เรียกใช้ AJAX ส่งข้อมูลไปที่ server
  //   $.ajax({
  //     url: `${window.serverUrl}/workout/save`, // เปลี่ยน URL ให้ถูกต้อง
  //     type: "POST",
  //     data: JSON.stringify(postData),
  //     dataType: "json",
  //     contentType: "application/json",
  //     success: function (response) {
  //       $me.prop("disabled", false);
  //       overlay.style.display = "none";
  //       location.href = `${window.serverUrl}/workout`;
  //       console.log("Data saved successfully:", response);
  //       // สามารถเพิ่มการแสดงข้อความแจ้งผู้ใช้ว่าการบันทึกสำเร็จได้ที่นี่
  //     },
  //     error: function (jqXHR, textStatus, errorThrown) {
  //       console.error("Error saving data:", textStatus, errorThrown);
  //       // สามารถแจ้ง error ให้ผู้ใช้ทราบได้
  //     },
  //   });
  // });
  $("#btn-save").on("click", function () {
    let $me = $(this);

    // เก็บข้อมูลที่ต้องการส่ง และ trim เพื่อลบช่องว่างด้านหน้า-ด้านหลัง
    let workoutID = $.trim($("#modal-workout-id").val());
    let workoutTitle = $.trim($("#modal-workout-title").text());
    let workoutTime = $.trim($("#input-time").val());
    let calculatedCalories = $.trim($("#result-calories").text());

    // ตรวจสอบว่าค่าที่จำเป็นต้องมีไม่เป็นค่าว่าง
    if (workoutID === "" || workoutTitle === "" || workoutTime === "") {
      alert("กรุณากรอกข้อมูลให้ครบถ้วน");
      return false;
    }

    // (ถ้าต้องการตรวจสอบค่านี้ด้วย ให้เพิ่มเงื่อนไขตามที่ต้องการ)
    // ถ้า calculatedCalories เป็น 0 ก็อาจให้แจ้งว่า "คำนวณแคลอรี่ไม่ถูกต้อง" ได้

    // ดึง URL รูปจาก background-image
    let bgImage = $("#modal-workout-bg").css("background-image");
    bgImage = bgImage.replace(/^url\(["']?/, "").replace(/["']?\)$/, "");

    // จัดข้อมูลที่จะส่งไปในตัวแปร postData
    let postData = {
      id: workoutID,
      title: workoutTitle,
      time: workoutTime,
      calories: calculatedCalories,
      // background_image: bgImage
    };

    const overlay = document.getElementById("processingOverlay");
    overlay.style.display = "flex";

    // เรียกใช้ AJAX ส่งข้อมูลไปที่ server
    $.ajax({
      url: `${window.serverUrl}/workout/save`, // เปลี่ยน URL ให้ถูกต้อง
      type: "POST",
      data: JSON.stringify(postData),
      dataType: "json",
      contentType: "application/json",
      success: function (response) {
        $me.prop("disabled", false);
        overlay.style.display = "none";
        location.href = `${window.serverUrl}/workout`;
        console.log("Data saved successfully:", response);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Error saving data:", textStatus, errorThrown);
      },
    });
  });

  $(".close-btn").on("click", function (e) {
    e.stopPropagation(); // ป้องกัน event bubble ไปที่การ์ด
    var $card = $(this).closest(".adminuiux-card");
    var workoutId = $card.data("workout-id");

    if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?")) {
      $.ajax({
        url: `${serverUrl}/workout/delete`,
        type: "POST",
        data: JSON.stringify({ workout_id: workoutId }),
        contentType: "application/json; charset=utf-8",
        success: function (response) {
          if (response.success) {
            alert("ลบรายการสำเร็จ!");
            console.log($card);
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
