<div class="my-3 text-center">
    <h2 id="totalCalToday"><?php echo $caloriesToDay; ?> </h2>
    <p>แคลที่เผาผลาญไป</p>
</div>

<style>
    .coverimg {
        width: 70px !important;
    }
</style>
<?php foreach ($userWorkouts as $key => $workout) { ?>
    <div class="col-12 mb-3">
        <div class="card adminuiux-card border-0 bg-gradient-<?php echo ($key + 1); ?> mt-3" data-workout-id="<?php echo $workout->id; ?>">
            <div class="card-body position-relative z-index-1">

                <!-- ปุ่มปิด -->
                <button class="close-btn position-absolute top-0 end-0 m-2 border-0 bg-transparent">
                    ✕
                </button>

                <div class="row">

                    <div class="col-3">
                        <figure class="height-70 w-100 rounded coverimg mb-0">
                            <img src="<?php echo base_url('assets/img/workout/' . $workout->icon); ?>" alt="" />
                        </figure>
                    </div>
                    <div class="col-9">
                        <h6 class="text-truncated">
                            <?php echo $workout->title; ?>
                        </h6>
                        <p class="text-secondary fs-14 mb-2">
                            <span class="me-1"><i class="bi bi-clock me-1"></i> <?php echo $workout->time; ?> นาที</span>
                            <span class="me-1"><i class="bi bi-fire me-1"></i> <?php echo $workout->calories; ?> แคลอรี่</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="adminuiux-mobile-footer hide-on-scrolldown pb-ios">
    <div class="p-3">
        <a href="<?php echo base_url('/workout/add'); ?>" class="btn btn-theme w-100">
            เพิ่มการออกกำลังกาย
        </a>
    </div>
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