<style>
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
</style>
<div class="my-3 text-center">
    <h2 id="totalCalToday">สวัสดี</h2>
    <p>เลือกการออกกำลังกายวันนี้ของคุณ</p>
</div>

<div class="row">
    <!-- วนลูปแสดง Workout -->
    <?php foreach ($workouts as $workout) { ?>
        <div class="col-4 text-center mb-4 icon-item <?php if ($workout->id == 12) echo 'disabled'; ?>">
            <!-- 
          data-bs-toggle="modal" และ data-bs-target="#addappointment" 
          เอาไว้สั่งเปิด Modal ของ Bootstrap 
          ส่วน data-* เอาไว้ส่งข้อมูล Workout ไปใน Modal ผ่าน jQuery 
        -->
            <a href="#"
                class="text-decoration-none text-white open-workout-modal"
                data-bs-toggle="modal"
                data-bs-target="#addappointment"
                data-workout-id="<?php echo $workout->id; ?>"
                data-workout-title="<?php echo $workout->title; ?>"
                data-workout-icon="<?php echo base_url('assets/img/workout/' . $workout->icon); ?>"
                data-workout-met="<?php echo $workout->MET; ?>">
                <img
                    src="<?php echo base_url('assets/img/workout/' . $workout->icon); ?>"
                    alt="<?php echo $workout->title; ?>"
                    class="img-fluid" />
                <div><?php echo $workout->title; ?></div>
            </a>
        </div>
    <?php } ?>
</div>

<!-- Modal -->
<div
    class="modal adminuiux-modal fade"
    id="addappointment"
    tabindex="-1"
    aria-labelledby="addappointmentmodalLabel">
    <div class="modal-dialog maxwidth-320 mx-auto modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-header position-relative">
                <!-- ภาพพื้นหลังใน Modal Header -->
                <figure
                    id="modal-workout-bg"
                    class="h-100 w-100 coverimg blur-overlay position-absolute start-0 top-0 z-index-0 opacity-75">
                    <!-- ใส่ id เพื่อเปลี่ยนรูปจาก jQuery -->
                    <img id="modal-workout-bg" src="" alt="Workout Image" />
                </figure>
                <div
                    class="row gx-3 align-items-center z-index-1 position-relative w-100">
                    <div class="col-auto">
                        <!-- รูปโปรไฟล์เล็ก (จะใช้รูปเดียวกับ BG ก็ได้) -->
                        <figure id="modal-workout-avatar" class="avatar avatar-50 coverimg rounded-circle">
                            <img id="modal-workout-avatar" src="" alt="Workout Avatar" />
                        </figure>
                    </div>
                    <div class="col">
                        <!-- ชื่อการออกกำลังกาย -->
                        <h5 class="mb-0" id="modal-workout-title">ชื่อการออกกำลังกาย</h5>
                        <p id="workout-example-calculate" class="small text-secondary mb-0">ตัวอย่างการคำนวณแคลอรี่</p>
                    </div>
                </div>
            </div>
            <div class="modal-body py-0">
                <div class="mb-3 mt-3">
                    <label for="input-time" class="form-label">ระยะเวลา (นาที)</label>
                    <input
                        type="number"
                        class="form-control"
                        id="input-time"
                        placeholder="ใส่เวลาที่ออกกำลังกาย เช่น 30">
                </div>

                <!-- แสดงผลลัพธ์แคลอรี่ -->
                <div class="mb-3">
                    <label class="form-label">ผลคำนวณ</label>
                    <div class="alert alert-dark" id="result-calories">0</div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <input type="hidden" id="modal-workout-id" value="" />
                <button
                    type="button"
                    class="btn btn-link theme-red"
                    data-bs-dismiss="modal">
                    ยกเลิก
                </button>
                <button
                    type="button"
                    class="btn btn-theme"
                    id="btn-save">
                    บันทึก
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Processing Overlay (Circle Spinner) -->
<div class="processing-overlay" id="processingOverlay">
    <div class="spinner"></div>
    <p class="processing-text">กำลังประมวลผล...</p>
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
        <a href="<?php echo base_url(); ?>" class="nav-link">
          <i class="nav-icon bi bi-columns-gap"></i>
          <span class="nav-text">หน้าแรก</span></span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('/menu'); ?>"><span><i class="nav-icon bi bi-graph-up-arrow"></i>
            <span class="nav-text">กิน</span></span></a>
      </li>
    </ul>
  </div>
</footer>