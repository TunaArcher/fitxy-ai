<header class="adminuiux-header">
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <button class="btn btn-square btn-link" onclick="goBack()">
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

      <div class="input-group mb-3"><input class="form-control" placeholder="บอกสิ่งที่ชอบ สิ่งที่ไม่ชอบ หรือต้องการแบบไหน เช่น ไม่เอาผัก, ไม่เอาเนื้อหมู, ไม่เอาเนื้อวัว " value="">
        <div class="dropdown input-group-text border-start-0 p-0">
          <button class="btn btn-square btn-link caret-none" type="button" id="btnGenerateFood" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
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
                  <div class="avatar avatar-30 rounded">1</div>
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Mon.
                  </p>
                  <div class="avatar avatar-30 rounded">2</div>
                </div>
                <div class="swiper-slide text-center pt-1 active">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Tue.
                  </p>
                  <div class="avatar avatar-30 rounded">3</div>
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Wed.
                  </p>
                  <div class="avatar avatar-30 rounded">4</div>
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Thu.
                  </p>
                  <div class="avatar avatar-30 rounded">5</div>
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Fri.
                  </p>
                  <div class="avatar avatar-30 rounded">6</div>
                </div>
                <div class="swiper-slide text-center pt-1">
                  <p
                    class="small text-secondary text-uppercase text-truncated mb-0">
                    Sat.
                  </p>
                  <div class="avatar avatar-30 rounded">7</div>
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
          // สมมุติว่าคุณมีตัวแปร $food ที่เป็น stdClass Object จากฐานข้อมูล
          // $food->list เป็น JSON string เราจะ decode ให้เป็น Array
          if ($foodTable->list) {
            $meals = json_decode($foodTable->list, true);

            // กำหนดลำดับของวัน
            $days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
          }

          ?>

          <!-- Render รายการอาหารสำหรับแต่ละวัน -->
          <?php foreach ($days as $day): ?>
            <div class="card-body height-dynamic overflow-auto pb-0" style="--h-dynamic: 340px;" data-day="<?php echo $day; ?>">
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
                              <?php echo htmlspecialchars($meal['menu_name']); ?> | <?php echo htmlspecialchars($meal['cal']); ?> พลังงาน
                            </h6>
                            <p class="text-secondary small text-truncated">Ads.</p>
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

  </main>
</div>