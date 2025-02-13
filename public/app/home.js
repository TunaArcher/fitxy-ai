$(document).ready(function () {
  console.log(window.customer);
  if (window.customer === null) {
    // กำหนดค่าเบื้องต้น
    var grantType = "authorization_code";
    var callbackUri = `${window.serverUrl}/callback`;
    var clientId = "2006891812";

    // ฟังก์ชันสร้างค่า state แบบสุ่ม (16 bytes) แล้วแปลงเป็น hex
    function generateState() {
      var array = new Uint8Array(16);
      window.crypto.getRandomValues(array);
      return Array.from(array, (dec) =>
        ("0" + dec.toString(16)).slice(-2)
      ).join("");
    }
    var state = generateState();

    // เก็บค่า state ใน sessionStorage (ใช้แทนการเก็บในเซสชันบน server)
    sessionStorage.setItem("oauth_state", state);

    // สร้างพารามิเตอร์สำหรับ LINE Login URL
    var params = {
      response_type: "code",
      client_id: clientId,
      redirect_uri: callbackUri,
      scope: "profile openid email",
      state: state,
    };

    // ใช้ $.param() ในการสร้าง query string
    var lineLoginUrl =
      "https://access.line.me/oauth2/v2.1/authorize?" + $.param(params);

    // สามารถนำ lineLoginUrl ไปใช้งาน เช่น เปลี่ยน location.href เพื่อ redirect
    window.location.href = lineLoginUrl;
  }
});
