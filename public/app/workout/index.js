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

  // เมื่อเปิด modal ให้เติมข้อมูลด้วยข้อมูลจาก element ที่มี data-*
  $(".open-workoutother-modal").on("click", function (e) {
    e.preventDefault();

    let id = $(this).data("workout-id");
    let title = $(this).data("workout-title");
    let icon = $(this).data("workout-icon");
    let met = $(this).data("workout-met");

    // อัปเดตข้อมูลใน Modal โดยใช้ class
    $(".modal-workoutother-id").val(id);
    $(".modal-workout-title").text(title);
    $(".modal-workout-bg").css("background-image", "url(" + icon + ")");
    $(".modal-workout-avatar").css("background-image", "url(" + icon + ")");
    $(".workout-example-calculate").text(
      `(MET: ${met}) x ${window.userWeight} x นาที x 0.0175`
    );

    currentMet = parseFloat(met) || 0;

    // รีเซ็ตค่าใน textarea
    $(".input-description").val("");
  });

  // เมื่อกดปุ่มวิเคราะห์
  $("#btn-analyze").on("click", function () {
    let $me = $(this);
    var $modal = $("#workoutOther");
    // ใช้ class แทน id ในการเข้าถึง textarea และ input hidden
    var workoutDescription = $(".input-description").val();
    var workoutId = $(".modal-workoutother-id").val();

    const overlay = document.getElementById("processingOverlay");
    overlay.style.display = "flex";

    $.ajax({
      url: `${serverUrl}/workout/calculate`,
      type: "POST",
      data: JSON.stringify({
        description: workoutDescription,
        workout_id: workoutId,
      }),
      contentType: "application/json",
      success: function (response) {

        if (response.success) {
          let $data = response.data;
          // สมมติว่า response ส่งกลับมาเป็น JSON object ที่มี key:
          // analysis, calories, minutes
          var analysis = $data.analysis;
          var calories = $data.calories;
          var minutes = $data.minutes;
  
          // สร้าง layout สำหรับแสดงผลหลังวิเคราะห์
          var newLayout = `
          <div class="modal-header">
            <h5 class="modal-title">สรุปผลการออกกำลังกายวันนี้</h5>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">แคลอรี่ที่เผาผลาญ</label>
              <div class="alert alert-success">${calories} แคล</div>
            </div>
            <div class="mb-3">
              <label class="form-label">เวลาที่ออกกำลังกาย (นาที)</label>
              <div class="alert alert-info">${minutes} นาที</div>
            </div>
            <hr>
            <div class="mb-3">
              <label class="form-label">ผลวิเคราะห์การออกกำลังกาย</label>
              <div class="alert alert-primary">${analysis}</div>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-theme btn-save" id="btn-save-workoutother">บันทึก</button>
          </div>
        `;
  
          // ค้นหา element .modal-content แล้วแทนที่เนื้อหาด้วย layout ใหม่
          var $modalContent = $modal.find(".modal-content");
          $modalContent.html(newLayout);
  
          // บันทึกข้อมูลสำคัญไว้ใน element ด้วย .data()
          $modalContent.data("analysis", analysis);
          $modalContent.data("calories", calories);
          $modalContent.data("minutes", minutes);
          $modalContent.data("workoutDescription", workoutDescription);
          $modalContent.data("workoutId", workoutId);
  
        } else {
          alert('ไม่สามารถค้นหาค่า MET ของกิจกรรมออกกำลังกายของคุณได้')
        }

        $me.prop("disabled", false);
        overlay.style.display = "none";
      },
      error: function (xhr, status, error) {
        console.error("เกิดข้อผิดพลาดในการวิเคราะห์: " + error);
      },
    });
  });

  // ใช้ delegated event binding สำหรับปุ่มที่ถูกสร้างขึ้นใหม่
  $(document).on("click", "#btn-save-workoutother", function () {
    let $me = $(this);

    var $modal = $("#workoutOther");
    var $modalContent = $modal.find(".modal-content");

    // ดึงข้อมูลที่บันทึกไว้ใน .modal-content
    var analysis = $modalContent.data("analysis");
    var calories = $modalContent.data("calories");
    var minutes = $modalContent.data("minutes");
    var workoutDescription = $modalContent.data("workoutDescription");
    var workoutId = $modalContent.data("workoutId");

    const overlay = document.getElementById("processingOverlay");
    overlay.style.display = "flex";

    // ส่ง AJAX บันทึกข้อมูลลงฐานข้อมูล
    $.ajax({
      url: `${serverUrl}/workout/save`,
      type: "POST",
      data: JSON.stringify({
        id: workoutId,
        title: workoutDescription,
        calories: calories,
        time: minutes,
        analysis: analysis,
      }),
      contentType: "application/json",
      success: function (saveResponse) {
        $me.prop("disabled", false);
        overlay.style.display = "none";
        location.href = `${window.serverUrl}/workout`;
        console.log("Data saved successfully:", response);
      },
      error: function (xhr, status, error) {
        console.error("เกิดข้อผิดพลาดในการบันทึก: " + error);
      },
    });
  });
});
