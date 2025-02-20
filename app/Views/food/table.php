<style>
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
<div id="title" class="d-none">ตารางอาหาร</div>
<div class="adminuiux-wrap">
  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">

      <div class="my-3 text-center">
        <h2 id="totalCalToday">ตารางอาหาร</h2>
        <p>ออกแบบตารางอาหารดังใจคุณ</p>
      </div>

      <div class="input-group mb-3"><input class="form-control" placeholder="ระบุความต้องการ เช่น ไม่เอาผัก, ไม่เอาเนื้อหมู, ไม่เอาเนื้อวัว " value="">
        <div class="dropdown input-group-text border-start-0 p-0">
          <button class="btn btn-square btn-link caret-none gradient-badge" type="button" id="btnGenerateFood" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
            <i class="bi bi-robot"></i> วิเคาะห์
          </button>
        </div>
      </div>

      <div class="col-12">
        <div class="card adminuiux-card mb-3">
          <div class="card-body">
            <div class="swiper swipernav dateselect">
              <div class="swiper-wrapper">
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Sun.
                  </p>
                  <!-- <div class="avatar avatar-30 rounded">1</div> -->
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Mon.
                  </p>
                  <!-- <div class="avatar avatar-30 rounded">2</div> -->
                </div>
                <div class="swiper-slide text-center pt-1 active">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Tue.
                  </p>
                  <!-- <div class="avatar avatar-30 rounded">3</div> -->
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Wed.
                  </p>
                  <!-- <div class="avatar avatar-30 rounded">4</div> -->
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Thu.
                  </p>
                  <!-- <div class="avatar avatar-30 rounded">5</div> -->
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Fri.
                  </p>
                  <!-- <div class="avatar avatar-30 rounded">6</div> -->
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Sat.
                  </p>
                  <!-- <div class="avatar avatar-30 rounded">7</div> -->
                </div>
              </div>
            </div>
          </div>

          <?php

          // สมมุติว่า $meals คือ array ที่ได้จาก json_decode() ของฟิลด์ list
          // ตัวอย่าง:
          // $meals = json_decode($food->list, true);

          // Mapping สำหรับชื่อมื้ออาหารเป็นภาษาไทย
          $mealLabels = [
            "breakfast" => "มื้อเช้า",
            "lunch"     => "มื้อเที่ยง",
            "dinner"    => "มื้อเย็น",
            "snack"     => "อาหารว่าง"
          ];

          ?>
          <?php

          // กำหนดลำดับของวัน
          $days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

          // สมมุติว่าคุณมีตัวแปร $food ที่เป็น stdClass Object จากฐานข้อมูล
          // $food->list เป็น JSON string เราจะ decode ให้เป็น Array
          if ($foodTable) {
            $meals = json_decode($foodTable->list, true);
          }

          ?>

          <!-- Render รายการอาหารสำหรับแต่ละวัน -->
          <?php foreach ($days as $day): ?>
            <div class="card-body height-dynamic overflow-auto pb-0" style="--h-dynamic: 360px;" data-day="<?php echo $day; ?>">
              <?php if (isset($meals[$day]) && is_array($meals[$day])): ?>
                <?php foreach ($mealLabels as $mealType => $label): ?>
                  <?php if (isset($meals[$day][$mealType])):
                    $meal = $meals[$day][$mealType];
                  ?>
                    <div class="card mb-2">
                      <div class="card-body">
                        <p class="mb-3 small fw-medium text-secondary">
                          <?php echo $label; ?> <span class="text-warning bi bi-tag"></span>
                        </p>
                        <div class="row align-items-center gx-2 mb-0">
                          <div class="col-auto">
                            <img src="<?php echo htmlspecialchars($meal['url']); ?>" class="avatar avatar-40 rounded" alt="">
                          </div>
                          <div class="col">
                            <h6 class="mb-0">
                              <?php echo htmlspecialchars($meal['menu_name']); ?>
                            </h6>
                            <p class="text-secondary small text-truncated"><i class="bi bi-fire me-1"></i> <?php echo htmlspecialchars($meal['cal']); ?> พลังงาน</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="text-center">ไม่มีข้อมูลสำหรับวันนี้ (<?php echo strtoupper($day); ?>)</p>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>

        </div>

        <button id="btnSaveFood" class="btn btn-primary w-100 mb-3" style="display: none;">เลือก ฉันชอบตารางนี้</button>

      </div>

    </div>

    <!-- Processing Overlay (Circle Spinner) -->
    <div class="processing-overlay" id="processingOverlay">
      <div class="spinner"></div>
      <p class="processing-text">รอสักครู่นะ</p>
      <h2 class="processing-text">FitXy-AI กำลังจัดให้ ...</h2>
    </div>

  </main>
</div>


<footer class="adminuiux-mobile-footer hide-on-scrolldown style-1">
  <div class="container">
    <ul class="nav nav-pills nav-justified">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('/workout'); ?>"><span><svg
              xmlns="http://www.w3.org/2000/svg"
              class="nav-icon"
              viewBox="0 0 20 10">
              <g id="workout-icon" transform="translate(-87 -157)">
                <g
                  id="Rectangle_32"
                  data-name="Rectangle 32"
                  transform="translate(87 159)"
                  fill="none"
                  stroke=""
                  stroke-width="1">
                  <rect width="4" height="8" rx="2" stroke="none" />
                  <rect
                    x="0.5"
                    y="0.5"
                    width="3"
                    height="7"
                    rx="1.5"
                    fill="none" />
                </g>
                <g
                  id="Rectangle_36"
                  data-name="Rectangle 36"
                  transform="translate(93 161)"
                  fill="none"
                  stroke=""
                  stroke-width="1">
                  <rect width="8" height="4" stroke="none" />
                  <rect x="0.5" y="0.5" width="7" height="3" fill="none" />
                </g>
                <g
                  id="Rectangle_34"
                  data-name="Rectangle 34"
                  transform="translate(90 157)"
                  fill="none"
                  stroke=""
                  stroke-width="1">
                  <rect width="4" height="12" rx="2" stroke="none" />
                  <rect
                    x="0.5"
                    y="0.5"
                    width="3"
                    height="11"
                    rx="1.5"
                    fill="none" />
                </g>
                <g
                  id="Rectangle_35"
                  data-name="Rectangle 35"
                  transform="translate(100 157)"
                  fill="none"
                  stroke=""
                  stroke-width="1">
                  <rect width="4" height="12" rx="2" stroke="none" />
                  <rect
                    x="0.5"
                    y="0.5"
                    width="3"
                    height="11"
                    rx="1.5"
                    fill="none" />
                </g>
                <g
                  id="Rectangle_33"
                  data-name="Rectangle 33"
                  transform="translate(103 159)"
                  fill="none"
                  stroke=""
                  stroke-width="1">
                  <rect width="4" height="8" rx="2" stroke="none" />
                  <rect
                    x="0.5"
                    y="0.5"
                    width="3"
                    height="7"
                    rx="1.5"
                    fill="none" />
                </g>
              </g>
            </svg>
            <span class="nav-text">ออกกำลังกาย</span></span></a>
      </li>
      <li class="nav-item">
        <a href="<?php base_url(); ?>" class="nav-link">
          <i class="nav-icon bi bi-columns-gap"></i>
          <span class="nav-text">หน้าแรก</span></span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('/menu'); ?>"><span><i class="nav-icon bi bi-graph-up-arrow"></i><span class="nav-text">กิน</span></span></a>
      </li>
    </ul>
  </div>
</footer>