$(document).ready(function () {
  $("#button-addon2").click(function () {
    // สร้าง element ชั่วคราวเพื่อคัดลอกข้อความ
    var tempInput = $("<input>");
    $("body").append(tempInput);

    // คัดลอกค่าจาก input ที่มี referral link
    tempInput.val($(".input-group input").val()).select();
    document.execCommand("copy");
    tempInput.remove(); // ลบ input ชั่วคราว

    // เปลี่ยน tooltip เป็น "Copied!"
    $(this).attr("data-bs-original-title", "Copied!").tooltip("show");
    alert("ส่งให้เพื่อนเลย");

    // เปลี่ยน tooltip กลับเป็น "Copy" หลังจาก 2 วินาที
    setTimeout(() => {
      $(this).attr("data-bs-original-title", "Copy");
    }, 2000);
  });

  // เปิดใช้งาน Bootstrap Tooltip
  $('[data-bs-toggle="tooltip"]').tooltip();
});
