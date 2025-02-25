<style>
  /* Progress Bar */
  .progress-container {
    width: 80%;
    height: 10px;
    background: #e0e0e0;
    margin: auto;
    border-radius: 5px;
    overflow: hidden;
  }

  .progress-bar {
    height: 100%;
    width: 20%;
    background: #03aed2;
    text-align: center;
    color: white;
    line-height: 10px;
    font-size: 12px;
  }

  /* ซ่อนแต่ละ Step (เฉพาะ .step.active จะแสดง) */
  .step {
    display: none;
  }

  .step.active {
    display: block;
  }

  /* เลย์เอาต์ของ Scroll Picker (ใช้ใน Step 2-4) */
  .picker-container {
    width: 100%;
    /* height: 500px; */
    height: 300px;
    position: relative;
    overflow: hidden;
    text-align: center;
  }

  .picker-container::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 80px;
    background: url("https://up2client.com/envato/vigor-pwa/main-file/assets/images/select-gender/select-bg-img.png") no-repeat center;
    opacity: 0.3;
    z-index: 1;
    pointer-events: none;
    transform: translateY(-50%);
  }

  .picker {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    overflow-y: scroll;
    scroll-snap-type: y mandatory;
    scrollbar-width: none;
    /* Firefox */
    -ms-overflow-style: none;
    /* IE 10+ */
  }

  .picker::-webkit-scrollbar {
    display: none;
  }

  .picker div {
    font-size: 24px;
    padding: 10px;
    /* color: #bbb; */
    transition: all 0.2s ease-in-out;
    scroll-snap-align: center;
  }

  .picker div.selected {
    font-size: 32px;
    font-weight: bold;
    /* color: black; */
    transform: scale(1.2);
  }

  /* สำหรับ Step 1: เลือกเพศ */
  .gender-options {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin: 20px 0;
  }

  .gender-option {
    padding: 10px 25px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    /* font-size: 24px; */
    transition: all 0.2s ease-in-out;
  }

  .gender-option.selected {
    border-color: #03aed2;
    background-color: #03aed2;
    color: white;
  }

  /* ปุ่ม Navigation */
  /* .multistep-form-btn {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
  } */

  /* .btn-next:disabled {
    background-color: #a0a0a0;
    cursor: not-allowed;
  } */

  /* สไตล์สำหรับ Radio Buttons ใน Step 5 */
  #exerciseOptions {
    /* max-width: 600px; */
    margin: auto;
    text-align: left;
    overflow-y: scroll;
    /* max-height: 500px; */
    padding-bottom: 120px;
  }

  #exerciseOptions .form-check {
    margin-bottom: 10px;
  }

  /* สไตล์สำหรับ Processing Overlay (Circle Spinner) */
  .processing-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  .spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #03aed2;
    border-radius: 50%;
    width: 200px;
    height: 200px;
    animation: spin 2s linear infinite;
    margin-bottom: 20px;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .processing-text {
    font-size: 20px;
    color: #333;
  }
</style>
<style>
  .option,
  .goal {
    border-radius: 8px;
    border: 2px solid var(--9, #F5F5F5);
    background: rgba(0, 0, 0, .3);
    padding: 12px;
    display: flex;
    align-items: center;
    column-gap: 16px;
  }

  .data-privacy-content h1,
  .manage-txt,
  .social-txt,
  .activity-details h3 {
    /* color: var(--4, #12151C); */
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    /* line-height: 24px; */
  }

  .activity-details p {
    margin-top: 4px;
  }

  .nested-accordion .comment,
  .account-back span,
  .activity-details p {
    color: var(--5, #555658);
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: 20px;
  }

  #exerciseOptions .selected {
    border-radius: 8px;
    border: 2px solid var(--2, #03aed2);
    background: var(--1, #FFF);
    color: #000;
  }
</style>
<style>
  .nav-fixed {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: 1320px;
    /* Default Bootstrap 5 container width */
    padding: 16px;
    z-index: 9;
  }

  /* ปรับตาม Bootstrap breakpoints */
  @media (max-width: 1400px) {
    .nav-fixed {
      max-width: 1140px;
      /* xl container */
    }
  }

  @media (max-width: 1200px) {
    .nav-fixed {
      max-width: 960px;
      /* lg container */
    }
  }

  @media (max-width: 992px) {
    .nav-fixed {
      max-width: 720px;
      /* md container */
    }
  }

  @media (max-width: 768px) {
    .nav-fixed {
      max-width: 540px;
      /* sm container */
    }
  }

  @media (max-width: 576px) {
    .nav-fixed {
      max-width: 100%;
      /* xs (เต็มจอ) */
    }
  }
</style>
<header class="adminuiux-header">
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <button class="btn btn-square btn-link" onclick="goBackHome()">
        <i class="bi bi-arrow-left"></i>
      </button>
      <p class="h6 my-1 px-3 text-center"><span class="title"></span></p>
      <div class="ms-auto"></div>
      <div class="ms-auto">
        <button
          class="btn btn-link btn-square btnsunmoon btn-link-header"
          id="btn-layout-modes-dark-page">
          <i class="sun mx-auto" data-feather="sun"></i>
          <i class="moon mx-auto" data-feather="moon"></i>
        </button>
      </div>
    </div>
  </nav>
</header>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    $("#title").length > 0 && $(".title").html($("#title").html());
  });
</script>
<div id="title" class="d-none">คำนวน TDEE</div>
<div class="adminuiux-wrap">
  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">

      <div class="progress mb-3 height-20" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar bg-theme-1" style="width: 25%">Step 1 of 5</div>
      </div>

      <!-- ฟอร์มแบ่งเป็น 5 ขั้นตอน -->
      <div id="formSteps">
        <!-- Step 1: เลือกเพศ -->
        <div class="step active text-center" data-step="1">
          <h2>เลือกเพศ</h2>
          <p>Please choose your gender.</p>
          <div class="gender-options">
            <div class="gender-option" data-value="ชาย"><img width="40" src="https://cdn-icons-png.flaticon.com/512/16967/16967045.png"> <span>ชาย</span></div>
            <div class="gender-option" data-value="หญิง"><img width="40" src="https://cdn-icons-png.flaticon.com/512/16967/16967039.png"> <span>หญิง</span></div>
            <!-- <div class="gender-option" data-value="LGBQ"><img width="40" src="https://cdn-icons-png.flaticon.com/512/4482/4482689.png"> <span>LGBQ</span></div> -->
          </div>
        </div>
        <!-- Step 2: เลือกอายุ -->
        <div class="step text-center" data-step="2">
          <h2>เลือกอายุ</h2>
          <p>Please provide your age in years.</p>
          <div class="picker-container">
            <div class="picker" id="agePicker"></div>
          </div>
        </div>
        <!-- Step 3: เลือกน้ำหนัก -->
        <div class="step text-center" data-step="3">
          <h2>เลือกน้ำหนัก</h2>
          <p>Please provide your weight in kilograms.</p>
          <div class="picker-container">
            <div class="picker" id="weightPicker"></div>
          </div>
        </div>
        <!-- Step 4: เลือกส่วนสูง -->
        <div class="step text-center" data-step="4">
          <h2>เลือกส่วนสูง</h2>
          <p>Please provide your height in centimeters.</p>
          <div class="picker-container">
            <div class="picker" id="heightPicker"></div>
          </div>
        </div>
        <!-- Step 5: เลือกระดับการออกกำลังกาย (ใช้ Option) -->
        <div class="step text-center" data-step="5">
          <h2>เลือก Lifestyle</h2>
          <p>Please select your Lifestyle.</p>
          <div id="exerciseOptions" class="mt-4">
            <div class="option text-start" id="activity1">
              <div class="activity-icon">
                <img src="https://up2client.com/envato/vigor-pwa/main-file/assets/images/select-gender/activity1.png" alt="activity-img">
              </div>
              <div class="activity-details text-start">
                <h3>Office-Based (ชีวิตประจำวันนั่งเป็นหลัก)</h3>
                <p>🏢 คนที่เหมาะสม: พนักงานออฟฟิศ, นักเรียน, คนทำงานจากบ้าน (WFH), คนที่เดินน้อย</p>
              </div>
            </div>
            <div class="option mt-2 text-start" id="activity2">
              <div class="activity-icon">
                <img src="https://up2client.com/envato/vigor-pwa/main-file/assets/images/select-gender/activity2.png" alt="activity-img">
              </div>
              <div class="activity-details text-start">
                <h3>Active Commuter (เดินเยอะระหว่างวัน)</h3>
                <p>🚶 คนที่เหมาะสม: พนักงานที่เดินไป-มาบ้าง, คนเดินทางเยอะ,</p>
              </div>
            </div>
            <div class="option mt-2 text-start selected" id="activity3">
              <div class="activity-icon">
                <img src="https://up2client.com/envato/vigor-pwa/main-file/assets/images/select-gender/activity3.png" alt="activity-img">
              </div>
              <div class="activity-details text-start">
                <h3>Active & Health (เคลื่อนไหวเยอะ)</h3>
                <p>💪 คนที่เหมาะสม: ทำงานที่ต้องยืน/เดินต่อเนื่องหลายชั่วโมง (เช่น พนักงานร้านอาหาร, พยาบาล)</p>
              </div>
            </div>
            <div class="option mt-2 text-start" id="activity4">
              <div class="activity-icon">
                <img src="https://up2client.com/envato/vigor-pwa/main-file/assets/images/select-gender/activity4.png" alt="activity-img">
              </div>
              <div class="activity-details text-start">
                <h3>High-Performance (ทำงานหนักและต่อเนื่อง)</h3>
                <p>🔥 คนที่เหมาะสม:ทำงานที่ใช้แรงกายหนัก (เช่น ก่อสร้าง, งานฟาร์ม, เกษตรกรรม)</p>
              </div>
            </div>
            <div class="option mt-2 text-start" id="activity5">
              <div class="activity-icon">
                <img src="https://up2client.com/envato/vigor-pwa/main-file/assets/images/select-gender/activity5.png" alt="activity-img">
              </div>
              <div class="activity-details text-start">
                <h3>Athlete & High-Intensity (นักกีฬา หรือคนที่ใช้ร่างกายหนักสุด)</h3>
                <p>🏆 คนที่เหมาะสม: นักกีฬาอาชีพ, ทหาร, คนที่ฝึกซ้อมหนักทุกวัน</p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Navigation Buttons ที่จะอยู่ติดขอบล่าง -->
      <div class="nav-fixed">
        <div class="bg-theme-1-subtle rounded px-3 py-2 mb-4">
          <div class="row">
            <div class="col">
              <button class="btn btn-accent me-3 my-2" id="backBtn" disabled>
                <i class="bi bi-arrow-left me-2"></i> ย้อนกลับ
              </button>
            </div>
            <div class="col-auto">
              <button class="btn btn-theme my-2" id="nextBtn" disabled>
                ถัดไป <i class="bi bi-arrow-right ms-2"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Processing Overlay (Circle Spinner) -->
    <div class="processing-overlay" id="processingOverlay">
      <div class="spinner"></div>
      <p class="processing-text">รอสักครู่นะ</p>
      <h2 class="processing-text">FitXy-AI กำลังจัดให้ ...</h2>
    </div>

    <div class="container mt-3 text-center" id="wrapper-result-tdee" style="display: none;">
      <h3>พลังงานที่ต้องการในแต่ละวัน (TDEE)</h3>
      <h1 id="tdee-result"></h1>
      <div id="table-result-tdee"></div>
    </div>
  </main>
</div>