<style>
  /* Container หลัก */
  #analyze {
    font-family: 'Helvetica', Arial, sans-serif;
    max-width: 600px;
    margin: 20px auto;
    border-radius: 6px;
    padding: 15px;
    font-size: 14px;
    /* ปรับลดขนาด font */
    line-height: 1.5;
  }

  /* ลบ Bullet ของรายการหลัก */
  #analyze>ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  /* สไตล์สำหรับรายการ (li) */
  #analyze li {
    margin-bottom: 12px;
  }

  /* สไตล์สำหรับข้อความใน p */
  #analyze p {
    margin: 0 0 8px;
  }

  /* เน้นข้อความที่เป็น strong */
  #analyze strong {
    color: #007bff;
    font-weight: 600;
  }

  /* รายการย่อย */
  #analyze ul ul {
    list-style-type: disc;
    padding-left: 20px;
    margin-top: 8px;
  }

  /* ปรับระยะห่างรายการสุดท้าย */
  #analyze li:last-child {
    margin-bottom: 0;
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
<div id="title" class="d-none">สรุป</div>
<div class="adminuiux-wrap">
  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">

      <div class="row gx-2 align-items-center mb-3">
        <div class="col-12">
          <div class="input-group"><input class="form-control text-center" id="datepickers">
            <div class="input-group-text" onclick="$(this).prev().click();"><i class="bi bi-calendar-event"></i></div>
          </div>
        </div>
      </div>

      <div class="row gx-3 align-items-center">
        <div class="col-12 col-lg-4">
          <div
            class="card adminuiux-card bg-theme-1-subtle border-0 theme-yellow mb-3" data-bs-toggle="modal" data-bs-target="#menuModal">
            <div class="card-body">
              <div class="row gx-3 align-items-center">
                <div class="col-auto">
                  <figure class="avatar avatar-60 rounded coverimg">
                    <img src="<?php echo base_url('/assets/img/splash_screen.gif'); ?>" alt="" />
                  </figure>
                </div>
                <div class="col">
                  <h6 id="cal_per_day">การทานแคลอรี่</h6>
                  <div
                    class="progress height-dynamic bg-theme-1-subtle mb-1"
                    role="progressbar"
                    aria-label="Basic example"
                    aria-valuenow="0"
                    aria-valuemin="0"
                    aria-valuemax="100">
                    <div
                      class="progress-bar height-dynamic rounded m-1 bg-theme-1"
                      style="width: 75%; --h-dynamic: 4px"></div>
                  </div>
                  <p class="small opacity-75" id="calDone">สำเร็จ 75%</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-6 col-lg-4">
          <div
            class="card adminuiux-card bg-theme-1-subtle border-0 theme-green mb-3">
            <div class="card-body">
              <div class="row gx-2 align-items-center">
                <div class="col-auto">
                  <div class="avatar avatar-50 mx-auto">
                    <h5 id="carbsValue"></h5>
                  </div>
                </div>
                <div class="col" data-bs-toggle="modal" data-bs-target="#menuModal">
                  <h6 class="mb-0">Carbs</h6>
                  <p class="small text-secondary">การทาน</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-6 col-lg-4">
          <div
            class="card adminuiux-card bg-theme-1-subtle border-0 theme-orange mb-3">
            <div class="card-body">
              <div class="row gx-2 align-items-center">
                <div class="col-auto">
                  <div class="avatar avatar-50 mx-auto">
                    <h5 id="proteinValue"></h5>
                  </div>
                </div>
                <div class="col" data-bs-toggle="modal" data-bs-target="#menuModal">
                  <h6 class="mb-0">Protein</h6>
                  <p class="small text-secondary">การทาน</p>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>

      <div class="card adminuiux-card mb-3">
        <div class="card-header">
          <div class="row gx-2 align-items-center">
            <div class="col">
              <h6>Calorie Stats</h6>
            </div>
          </div>
        </div>
        <div class="card-body px-0">
          <div class="height-170"><canvas id="summarychart"></canvas></div>
        </div>
      </div>

      <div class="row gx-3 align-items-center">
        <div class="col-12 col-lg-6">
          <div class="card adminuiux-card mb-3">
            <div class="card-body text-center">
              <div class="position-absolute end-0 top-0 m-2" data-bs-toggle="modal" data-bs-target="#workoutModal">
                <span class="badge badge-light text-bg-theme-1 theme-yellow"><i class="bi bi-fire"></i> การเผาผลาญ</span>
              </div>
              <div
                class="avatar avatar-180 mx-auto position-relative mx-auto my-4">
                <!-- <div id="circleprogressblue12"></div>
                <div class="h-100 w-100 top-0 start-0 position-absolute">
                  <div
                    class="row h-100 align-items-center justify-content-center theme-green">
                    <div class="col-auto lh-20">
                      <h1 class="mb-0">
                        <i class="bi bi-person-walking text-theme-1"></i>
                      </h1>
                      <h2 class="mb-0">6251</h2>
                      <p class="small text-secondary">Steps</p>
                    </div>
                  </div>
                </div> -->
                <img src="https://i.pinimg.com/originals/bf/23/08/bf2308cd01fbd8fe43bf6ac3d864c03c.gif" alt="">
              </div>
              <div class="row align-items-center">
                <div class="col text-center">
                  <h5 class="mb-0" id="workoutSummaryTodayTime">2 hrs</h5>
                  <p class="text-secondary small">Workout</p>
                </div>
                <div class="col text-center">
                  <h5 class="mb-0" id="workoutSummaryTodayCalories">389 kcal</h5>
                  <p class="text-secondary small">Burned</p>
                </div>
                <!-- <div class="col text-end">
                  <h5 class="mb-0">3.2 km</h5>
                  <p class="text-secondary small">Running</p>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6" id="wrapperAnalyze" style="display: none;">
        <div class="card adminuiux-card bg-theme-1-subtle mb-3">
          <div class="card-body">
            <h5 class="mb-3">ผลการวิเคราะห์</h5>
            <div id="analyze"></div>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>

<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title h5" id="menuModalLabel">รายการการกิน</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-12 mb-3">

          <div class="card adminuiux-card" data-menu-id="129">
            <div class="card-body pt-2">
              <div class="row gx-3 align-items-center mb-2" data-bs-toggle="modal" data-bs-target="#standardmodal">
                <div class="col">
                  <h6 class="text-truncated">ปลาแซลมอนย่าง + บรอกโคลี, แครอท, เห็ดผัดกระเท</h6>
                </div>
              </div>
              <div class="row gx-3 align-items-center" data-bs-toggle="modal" data-bs-target="#standardmodal">
                <div class="col-4">
                  <figure class="height-50 w-100 rounded coverimg mb-0" style="background-image: url(&quot;https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line_agent/line_67bd507759742.jpg&quot;);"><img src="https://autoconx.sgp1.digitaloceanspaces.com/uploads/img/line_agent/line_67bd507759742.jpg" alt="" style="display: none;"></figure>
                </div>
                <div class="col-8">
                  <div class="row gx-3">
                    <div class="col">
                      <p class="small mb-0">15 g</p>
                      <p class="fs-12 opacity-75">Carbs</p>
                    </div>
                    <div class="col">
                      <p class="small mb-0">25 g</p>
                      <p class="fs-12 opacity-75">Protein</p>
                    </div>
                    <div class="col">
                      <p class="small mb-0">20 g</p>
                      <p class="fs-12 opacity-75">Fat</p>
                    </div>
                    <div class="col">
                      <p class="small mb-0 menu-cal">350 kcal</p>
                      <p class="fs-12 opacity-75">Energy</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<div class="modal fade" id="workoutModal" tabindex="-1" aria-labelledby="workoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <p class="modal-title h5" id="workoutModalLabel">รายการออกกำลังกาย</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-12 mb-3">
          <div class="card adminuiux-card border-0 bg-gradient-1 mt-3" data-workout-id="1">
            <div class="card-body position-relative z-index-1">
              <div class="row">

                <div class="col-3">
                  <figure class="height-70 w-100 rounded coverimg coverimg-x mb-0" style="background-image: url(&quot;http://localhost:8888/assets/img/workout/content-nav-watch-workout-walking-icon.png&quot;);">
                    <img src="http://localhost:8888/assets/img/workout/content-nav-watch-workout-walking-icon.png" alt="" style="display: none;">
                  </figure>
                </div>
                <div class="col-9">
                  <h6 class="text-truncated">
                    เดิน </h6>
                  <p class="text-secondary fs-14 mb-2">
                    <span class="me-1"><i class="bi bi-clock me-1"></i> 52 นาที</span>
                    <span class="me-1"><i class="bi bi-fire me-1"></i> 207.03 แคลอรี่</span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<?php echo view('layouts/bottom_menu'); ?>