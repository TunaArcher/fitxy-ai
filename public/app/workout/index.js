// โหลด jQuery ก่อน จากนั้นโหลด Bootstrap JS (แนะนำให้ใส่สคริปต์ด้านล่างก่อนปิด </body>)
$(document).ready(function() {
    let currentMet = 0; // เก็บค่า MET ของ workout ปัจจุบัน
    
    // ฟังก์ชันคำนวณแคลอรี่ (สูตร: MET × Weight(kg) × Time(minutes) × 0.0175)
    function calculateCalories() {
      let weight = parseFloat(window.userWeight) || 0;
      let time = parseFloat($('#input-time').val()) || 0;
      let calories = currentMet * weight * time * 0.0175;
      $('#result-calories').text(calories.toFixed(2));
    }
    
    // เมื่อคลิกเลือก Workout ให้เปิด Modal และเซตข้อมูลใน Modal
    $('.open-workout-modal').on('click', function(e) {
      e.preventDefault();

      let id = $(this).data('workout-id');
      let title = $(this).data('workout-title');
      let icon = $(this).data('workout-icon');
      let met = $(this).data('workout-met');
      
      // อัปเดตข้อมูลใน Modal
      $('#modal-workout-id').val(id);
      $('#modal-workout-title').text(title);
      $('#modal-workout-bg').css('background-image', 'url(' + icon + ')');
      $('#modal-workout-avatar').css('background-image', 'url(' + icon + ')');
      $('#workout-example-calculate').text(`(MET: ${met}) x ${window.userWeight} x นาที x 0.0175`);
      
      currentMet = parseFloat(met) || 0;
      
      // รีเซ็ตค่าใน input และผลลัพธ์
      $('#input-time').val('');
      $('#result-calories').text('0');
    });
    
    // คำนวณแคลอรี่เมื่อเปลี่ยนค่าในช่อง input ระยะเวลา
    $('#input-time').on('input', calculateCalories);
    
    // เมื่อกดปุ่ม "บันทึก" ส่งข้อมูลไปที่ server ผ่าน AJAX
    $('#btn-save').on('click', function() {

        let $me = $(this);
      

        
      // เก็บข้อมูลที่ต้องการส่ง
      let workoutID = $('#modal-workout-id').val();
      let workoutTitle = $('#modal-workout-title').text();
    //   let workoutMet = currentMet;
      let workoutTime = $('#input-time').val();
      let calculatedCalories = $('#result-calories').text();
    //   let userWeight = window.userWeight; // สมมติว่ามีการกำหนดไว้แล้ว
      
      // ดึง URL รูปจาก background-image
      let bgImage = $('#modal-workout-bg').css('background-image');
      // ตัวอย่าง bgImage จะอยู่ในรูปแบบ: url("http://example.com/path/image.jpg")
      // เราแปลงให้เป็น URL ที่เรียบง่าย
      bgImage = bgImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
      
      // จัดข้อมูลที่จะส่งไปในตัวแปร postData
      let postData = {
        id: workoutID,
        title: workoutTitle,
        // met: workoutMet,
        time: workoutTime,
        // weight: userWeight,
        calories: calculatedCalories,
        // background_image: bgImage
      };

      const overlay = document.getElementById("processingOverlay");
      overlay.style.display = "flex";  
      
      // เรียกใช้ AJAX ส่งข้อมูลไปที่ server
      $.ajax({
        url: `${window.serverUrl}/workout/save`, // เปลี่ยน URL ให้ถูกต้อง
        type: 'POST',
        data: JSON.stringify(postData),
        dataType: 'json',
        contentType: "application/json",
        success: function(response) {
            $me.prop("disabled", false);
            overlay.style.display = "none";
            location.href = `${window.serverUrl}/workout`;
          console.log('Data saved successfully:', response);
          // สามารถเพิ่มการแสดงข้อความแจ้งผู้ใช้ว่าการบันทึกสำเร็จได้ที่นี่
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.error('Error saving data:', textStatus, errorThrown);
          // สามารถแจ้ง error ให้ผู้ใช้ทราบได้
        }
      });
    });
  });
  