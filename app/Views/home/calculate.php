<div class="adminuiux-wrap">

  <main class="adminuiux-content has-sidebar" onclick="contentClick()">
    <div class="container mt-3" id="main-content">
      <div class="row gx-3 align-items-center">

        <div class="col-9 col-sm-8 col-md-9 col-xl mb-4">
          <h1 class="fw-bold text-theme-accent-1 mb-0">
            สวัสดีคุณ, <span><?php echo session()->get('displayName'); ?></span>
          </h1>
          <h4 class="text-theme-1">You have a fantastic day</h4>
        </div>

        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
          <div
            class="card adminuiux-card border-0 position-relative border-0 overflow-hidden blur-overlay mb-3">
            <div
              class="position-absolute top-0 start-0 h-100 w-100 coverimg opacity-75 z-index-0">
              <img src="<?php echo session()->get('pictureUrl'); ?>" alt="" />
            </div>
            <div class="card-header z-index-1">
              <div class="row align-items-center">
                <div class="col">
                  <h6> แคลวันนี้ X,xxx สู้สู้นะ</h6>
                </div>
                <div class="col-auto">
                  <span class="badge badge-light text-bg-theme-1 theme-orange">
                    <span class="bi bi-award text-theme-1"></span> เป้าหมาย
                  </span>
                </div>
              </div>
            </div>
            <div class="card-body py-0 z-index-1">
              <div class="height-170 text-center position-relative mb-3">
                <div
                  class="position-absolute top-50 start-50 translate-middle z-index-1 bg-white rounded-circle p-1">
                  <figure
                    class="avatar avatar-120 rounded-circle coverimg align-middle shadow-sm">
                    <img src="<?php echo session()->get('pictureUrl'); ?>" alt="" />
                  </figure>
                </div>
                <canvas
                  id="doughnutchart"
                  class="position-relative z-index-0 mx-auto"></canvas>
              </div>
              <div class="row">
                <div class="col-6 col-lg-6 mb-3">
                  <p class="small">
                    <span
                      class="me-1 avatar avatar-20 rounded bg-green"></span>
                    Exercise
                    <span class="text-success fw-normal ms-1">45%</span>
                  </p>
                </div>
                <div class="col-6 col-lg-6 mb-3">
                  <p class="small">
                    <span
                      class="me-1 avatar avatar-20 rounded bg-orange"></span>
                    Diet
                    <span class="text-success fw-normal ms-1">30%</span>
                  </p>
                </div>
                <div class="col-6 col-lg-6 mb-3">
                  <p class="small">
                    <span
                      class="me-1 avatar avatar-20 rounded bg-cyan"></span>
                    Medicine
                    <span class="text-success fw-normal ms-1">25%</span>
                  </p>
                </div>
                <div class="col-6 col-lg-6 mb-3">
                  <p class="small">
                    <span
                      class="me-1 avatar avatar-20 rounded bg-white"></span>
                    Other
                    <span class="text-success fw-normal ms-1">10%</span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- <div class="swiper swipernav mb-3">
            <div class="swiper-wrapper">
              <div class="swiper-slide width-140">
                <div class="card adminuiux-card theme-green">
                  <div class="card-header">
                    <div class="row">
                      <div class="col"><h6>Walk</h6></div>
                      <div class="col-auto">
                        <i class="bi bi-person-walking text-theme-1"></i>
                      </div>
                    </div>
                  </div>
                  <div class="card-body text-center pt-0">
                    <div class="avatar avatar-100 mx-auto position-relative">
                      <div id="circleprogressgreen2"></div>
                      <div class="position-absolute top-0 start-0 w-100 h-100">
                        <div
                          class="row align-items-center justify-content-center h-100"
                        >
                          <div class="col-auto lh-20">
                            <h4 class="h4 mb-0">7532</h4>
                            <p class="small opacity-75">Steps</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-140">
                <div class="card adminuiux-card theme-red">
                  <div class="card-header">
                    <div class="row">
                      <div class="col"><h6>Heart</h6></div>
                      <div class="col-auto">
                        <i class="bi bi-heart text-theme-1"></i>
                      </div>
                    </div>
                  </div>
                  <div class="card-body pt-0 px-0">
                    <div class="height-100 w-100 mx-auto position-relative">
                      <canvas id="lineheart"></canvas>
                      <div
                        class="position-absolute bottom-0 start-0 w-100 px-3"
                      >
                        <div
                          class="row align-items-center justify-content-center h-100"
                        >
                          <div class="col-12 lh-20">
                            <h4 class="h4 mb-0">80</h4>
                            <p class="small opacity-75">bpm</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-140">
                <div class="card adminuiux-card theme-purple">
                  <div class="card-header">
                    <div class="row">
                      <div class="col"><h6>Sleep</h6></div>
                      <div class="col-auto">
                        <i class="bi bi-moon-stars text-theme-1"></i>
                      </div>
                    </div>
                  </div>
                  <div class="card-body text-center pt-0">
                    <div
                      class="avatar avatar-60 mx-auto position-relative mb-3"
                    >
                      <div id="circleprogresspurple1"></div>
                    </div>
                    <h4 class="h4 mb-0">
                      7.4 <span class="fw-normal fs-14 opacity-75">Hours</span>
                    </h4>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-140">
                <div class="card adminuiux-card theme-cyan">
                  <div class="card-header">
                    <div class="row">
                      <div class="col"><h6>Exercise</h6></div>
                      <div class="col-auto">
                        <i class="bi bi-bicycle text-theme-1"></i>
                      </div>
                    </div>
                  </div>
                  <div class="card-body text-center pt-0">
                    <div class="height-100">
                      <canvas id="areachartblue1"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-140">
                <div class="card adminuiux-card theme-orange">
                  <div class="card-header">
                    <div class="row">
                      <div class="col"><h6>Calories</h6></div>
                      <div class="col-auto">
                        <i class="bi bi-speedometer text-theme-1"></i>
                      </div>
                    </div>
                  </div>
                  <div class="card-body text-center pt-0">
                    <div class="avatar avatar-100 mx-auto position-relative">
                      <div id="circleprogressorange1"></div>
                      <div class="position-absolute top-0 start-0 w-100 h-100">
                        <div
                          class="row align-items-center justify-content-center h-100"
                        >
                          <div class="col-auto lh-20">
                            <h4 class="h4 mb-0">365</h4>
                            <p class="small opacity-75">kcal</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
      <!-- <div class="swiper swipernav mb-3">
            <div class="swiper-wrapper">
              <div class="swiper-slide width-300">
                <div class="card adminuiux-card border-0 bg-gradient-7">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <h6 class="text-truncated">
                          Complete Morning Yoga Session
                        </h6>
                        <p class="text-secondary fs-14 mb-2">
                          <span class="me-1"
                            ><i class="bi bi-clock me-1"></i> 20 min</span
                          >
                          <span class="me-1"
                            ><i class="bi bi-fire me-1"></i> 250 kcal</span
                          >
                        </p>
                        <button class="btn btn-sm btn-link bg-white-opacity">
                          <i class="bi bi-play me-1"></i> Start
                        </button>
                      </div>
                      <div class="col-4">
                        <figure class="height-90 w-100 rounded coverimg mb-0">
                          <img src="assets/img/fitness/image-7.jpg" alt="" />
                        </figure>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-300">
                <div class="card adminuiux-card border-0 bg-gradient-3">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <h6 class="text-truncated">
                          Workout for belly fat reduce
                        </h6>
                        <p class="text-secondary fs-14 mb-2">
                          <span class="me-1"
                            ><i class="bi bi-clock me-1"></i> 22 min</span
                          >
                          <span class="me-1"
                            ><i class="bi bi-fire me-1"></i> 185 kcal</span
                          >
                        </p>
                        <button class="btn btn-sm btn-link bg-white-opacity">
                          <i class="bi bi-play me-1"></i> Start
                        </button>
                      </div>
                      <div class="col-4">
                        <figure class="height-90 w-100 rounded coverimg mb-0">
                          <img src="assets/img/fitness/image-8.jpg" alt="" />
                        </figure>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-300">
                <div class="card adminuiux-card border-0 bg-r-gradient">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <h6 class="text-truncated">
                          The great evening Yoga Session
                        </h6>
                        <p class="text-secondary fs-14 mb-2">
                          <span class="me-1"
                            ><i class="bi bi-clock me-1"></i> 18 min</span
                          >
                          <span class="me-1"
                            ><i class="bi bi-fire me-1"></i> 120 kcal</span
                          >
                        </p>
                        <button class="btn btn-sm btn-link bg-white-opacity">
                          <i class="bi bi-play me-1"></i> Start
                        </button>
                      </div>
                      <div class="col-4">
                        <figure class="height-90 w-100 rounded coverimg mb-0">
                          <img src="assets/img/fitness/image-3.jpg" alt="" />
                        </figure>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-300">
                <div class="card adminuiux-card border-0 bg-gradient-10">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <h6 class="text-truncated">
                          Routine Exercise for Legs
                        </h6>
                        <p class="text-secondary fs-14 mb-2">
                          <span class="me-1"
                            ><i class="bi bi-clock me-1"></i> 30 min</span
                          >
                          <span class="me-1"
                            ><i class="bi bi-fire me-1"></i> 300 kcal</span
                          >
                        </p>
                        <button class="btn btn-sm btn-link bg-white-opacity">
                          <i class="bi bi-play me-1"></i> Start
                        </button>
                      </div>
                      <div class="col-4">
                        <figure class="height-90 w-100 rounded coverimg mb-0">
                          <img src="assets/img/fitness/image-4.jpg" alt="" />
                        </figure>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-slide width-300">
                <div class="card adminuiux-card border-0 bg-gradient-6">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-8">
                        <h6 class="text-truncated">
                          Complete Morning Yoga Session
                        </h6>
                        <p class="text-secondary fs-14 mb-2">
                          <span class="me-1"
                            ><i class="bi bi-clock me-1"></i> 35 min</span
                          >
                          <span class="me-1"
                            ><i class="bi bi-fire me-1"></i> 220 kcal</span
                          >
                        </p>
                        <button class="btn btn-sm btn-link bg-white-opacity">
                          <i class="bi bi-play me-1"></i> Start
                        </button>
                      </div>
                      <div class="col-4">
                        <figure class="height-90 w-100 rounded coverimg mb-0">
                          <img src="assets/img/fitness/image-5.jpg" alt="" />
                        </figure>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
      <div class="row">
        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
          <div class="row gx-3">
            <div class="col-6">
              <a href="<?php echo base_url('/calculate'); ?>">
                <div class="card adminuiux-card border-0 height-150 bg-theme-l-gradient mb-3 hover-action">
                  <div class="card-body position-relative">
                    <h4 class="mb-0">คำนวน</h4>
                    <p class="opacity-75 mb-4">TEDD</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-6">
              <a href="<?php echo base_url('/'); ?>">
                <div class="card adminuiux-card border-0 height-150 bg-theme-l-gradient mb-3 hover-action">
                  <div class="card-body position-relative">
                    <h4 class="mb-0">Develop</h4>
                    <p class="opacity-75 mb-4">Develop</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-6">
              <a href="<?php echo base_url('/'); ?>">
                <div class="card adminuiux-card border-0 height-150 bg-theme-l-gradient mb-3 hover-action">
                  <div class="card-body position-relative">
                    <h4 class="mb-0">Develop</h4>
                    <p class="opacity-75 mb-4">Develop</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-6">
              <a href="<?php echo base_url('/'); ?>">
                <div class="card adminuiux-card border-0 height-150 bg-theme-l-gradient mb-3 hover-action">
                  <div class="card-body position-relative">
                    <h4 class="mb-0">Develop</h4>
                    <p class="opacity-75 mb-4">Develop</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
        <!-- <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
              <div class="card adminuiux-card mb-3">
                <div class="card-body">
                  <div class="swiper swipernav dateselect">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide text-center pt-1">
                        <p
                          class="small text-secondary text-uppercase text-truncated mb-0"
                        >
                          Wed
                        </p>
                        <div class="avatar avatar-30 rounded">4</div>
                      </div>
                      <div class="swiper-slide text-center pt-1">
                        <p
                          class="small text-secondary text-uppercase text-truncated mb-0"
                        >
                          Thu
                        </p>
                        <div class="avatar avatar-30 rounded">5</div>
                      </div>
                      <div class="swiper-slide text-center pt-1 active">
                        <p
                          class="small text-secondary text-uppercase text-truncated mb-0"
                        >
                          Fri
                        </p>
                        <div class="avatar avatar-30 rounded">6</div>
                      </div>
                      <div class="swiper-slide text-center pt-1">
                        <p
                          class="small text-secondary text-uppercase text-truncated mb-0"
                        >
                          Sat
                        </p>
                        <div class="avatar avatar-30 rounded">7</div>
                      </div>
                      <div class="swiper-slide text-center pt-1">
                        <p
                          class="small text-secondary text-uppercase text-truncated mb-0"
                        >
                          Sun
                        </p>
                        <div class="avatar avatar-30 rounded">8</div>
                      </div>
                      <div class="swiper-slide text-center pt-1">
                        <p
                          class="small text-secondary text-uppercase text-truncated mb-0"
                        >
                          Mon
                        </p>
                        <div class="avatar avatar-30 rounded">9</div>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="card-body height-dynamic overflow-auto pb-0"
                  style="--h-dynamic: 225px"
                >
                  <div class="card mb-2">
                    <div class="card-body">
                      <p class="mb-3 small fw-medium text-secondary">
                        10:00 am - 11:30 am
                        <span class="text-warning bi bi-tag"></span>
                      </p>
                      <div class="row align-items-center gx-2 mb-0">
                        <div class="col-auto">
                          <img
                            src="assets/img/fitness/image-11.jpg"
                            class="avatar avatar-40 rounded"
                            alt=""
                          />
                        </div>
                        <div class="col">
                          <h6 class="mb-0">Diet Management Event</h6>
                          <p class="text-secondary small text-truncated">
                            Sweden, Europe.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card bg-r-gradient mb-2">
                    <div class="card-body">
                      <p class="mb-3 small fw-medium text-secondary">
                        10:00 am - 11:30 am
                        <span class="text-warning bi bi-tag"></span>
                      </p>
                      <div class="row gx-2 mb-0">
                        <div class="col-auto">
                          <img
                            src="assets/img/modern-ai-image/user-4.jpg"
                            class="avatar avatar-40 rounded"
                            alt=""
                          />
                          <img
                            src="assets/img/fitness/image-10.jpg"
                            class="avatar avatar-40 rounded"
                            alt=""
                          />
                        </div>
                        <div class="col">
                          <h6 class="mb-0">Lunch out</h6>
                          <p class="text-secondary small text-truncated">
                            Chapter 2 starting.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card mb-2">
                    <div class="card-body">
                      <p class="mb-3 small fw-medium text-secondary">
                        10:00 am - 11:30 am
                        <span class="text-warning bi bi-tag"></span>
                      </p>
                      <div class="row gx-2 mb-0">
                        <div class="col-auto">
                          <img
                            src="assets/img/modern-ai-image/user-5.jpg"
                            class="avatar avatar-40 rounded"
                            alt=""
                          />
                          <img
                            src="assets/img/modern-ai-image/user-6.jpg"
                            class="avatar avatar-40 rounded"
                            alt=""
                          />
                        </div>
                        <div class="col">
                          <h6 class="mb-0">Noon Yoga Session</h6>
                          <p class="text-secondary small text-truncated">
                            By Jyotika Vaishnav.
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
        <!-- <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
              <div
                class="card adminuiux-card bg-theme-1 overflow-hidden position-relative mb-3"
              >
                <div
                  class="position-absolute start-0 top-0 h-100 w-100 rounded overflow-hidden coverimg z-index-0"
                >
                  <img src="assets/img/fitness/bg-overlay-1.png" alt="" />
                </div>
                <div
                  class="card-body height-dynamic position-relative"
                  style="--h-dynamic: 315px"
                >
                  <span
                    class="opacity-50 position-absolute end-0 top-0 m-1 mx-2 fs-12"
                    >Advertisement</span
                  >
                  <div
                    class="row align-items-center justify-content-center h-100"
                  >
                    <div class="col-11">
                      <h3 class="fw-normal mb-0">
                        Stress relief<br />with Om Aditya
                      </h3>
                      <p class="opacity-75">By: John Nicolas</p>
                      <h1 class="mb-0">20% OFF</h1>
                      <br />
                      <div class="row align-items-center">
                        <div class="col">
                          <button class="btn btn-outline-light">
                            Enroll Now
                          </button>
                        </div>
                        <div class="col-auto">
                          <small class="opacity-50">Code:</small><br />NKJF007
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
      </div>
    </div>
  </main>
</div>