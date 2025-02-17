<header class="adminuiux-header">
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo base_url(); ?>"><img data-bs-img="light" src="<?php echo base_url('assets/img/logo72x72.png'); ?>" alt="" />
        <img data-bs-img="dark" src="<?php echo base_url('assets/img/logo72x72.png'); ?>" alt="" />
        <div class="">
          <span class="h4">Fitness<span class="fw-bold"></span><span>AI</span></span>
          <p class="company-tagline">UnityX</p>
        </div>
      </a>
      <div class="flex-grow-1 px-3 justify-content-center">
        <div
          class="input-group input-group-md rounded search-wrap maxwidth-300 mx-auto d-none d-lg-flex shadow-sm">
          <span class="input-group-text border-0 bg-none"><i class="bi bi-search"></i> </span><input
            class="form-control border-0 bg-none"
            type="search"
            placeholder="Search here..."
            id="searchglobal" />
        </div>
      </div>
      <div class="ms-auto">
        <button
          class="btn btn-link btn-square btn-icon btn-link-header d-lg-none"
          type="button"
          onclick="openSearch()">
          <i data-feather="search"></i>
        </button>
        <button
          class="btn btn-link btn-square btnsunmoon btn-link-header"
          id="btn-layout-modes-dark-page">
          <i class="sun mx-auto" data-feather="sun"></i>
          <i class="moon mx-auto" data-feather="moon"></i>
        </button>


      </div>
    </div>
  </nav>
  <div class="adminuiux-search-full">
    <div class="row gx-2 align-items-center">
      <div class="col-auto">
        <button
          class="btn btn-link btn-square"
          type="button"
          onclick="closeSearch()">
          <i data-feather="arrow-left"></i>
        </button>
      </div>
      <div class="col">
        <input
          class="form-control pe-0 border-0"
          type="search"
          placeholder="Type something here..." />
      </div>
    </div>
  </div>
</header>
<div class="adminuiux-wrap">

  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">
      <div class="row gx-3 align-items-center">

        <?php
        $greetings = [
          "ขอให้เป็นวันที่ยอดเยี่ยม!",
          "วันนี้เป็นวันของคุณ จงทำให้ดีที่สุด!",
          "ขอให้มีวันที่เต็มไปด้วยพลังบวก!",
          "ขอให้วันนี้เป็นวันที่สดใส 😊",
          "ขอให้มีความสุขในทุกช่วงเวลาของวันนี้!",
          "ให้วันนี้เป็นวันที่เต็มไปด้วยรอยยิ้ม!",
          "ขอให้คุณพบแต่สิ่งดี ๆ ในวันนี้!",
          "ขอให้วันนี้เป็นวันที่พิเศษสำหรับคุณ!",
          "เริ่มต้นวันใหม่ด้วยพลังและความสุข!",
          "ขอให้เป็นวันที่น่าจดจำและเต็มไปด้วยแรงบันดาลใจ!"
        ];

        // สุ่มเลือกข้อความ
        $randomGreeting = $greetings[array_rand($greetings)];
        ?>

        <div class="col-12 mb-4">
          <h1 class="fw-bold text-theme-accent-1 mb-0">
            สวัสดีคุณ, <span><?php echo session()->get('user')->name; ?></span>
          </h1>
          <h4 class="text-theme-1"><?php echo $randomGreeting; ?></h4>
        </div>

        <div class="col-12">
          <div
            class="card adminuiux-card border-0 position-relative border-0 overflow-hidden blur-overlay mb-3">
            <div
              class="position-absolute top-0 start-0 h-100 w-100 coverimg opacity-75 z-index-0">
              <img src="<?php echo session()->get('user')->profile; ?>" alt="" />
            </div>
            <div class="card-header z-index-1">
              <div class="row align-items-center">

                <?php if (session()->get('user')->target == '') { ?>

                  <div class="col"></div>
                  <div class="col-auto">
                    <a href="<?php echo base_url('/calculate'); ?>">
                      <span class="badge badge-light text-bg-theme-1 theme-orange">
                        <span class="bi bi-award text-theme-1"></span> ไปคำนวน TDEE ก่อนนะ !
                      </span>
                    </a>
                  </div>

                <?php } else { ?>
                  <?php
                  $encouragements = [
                    "สู้ ๆ นะ! ✌️",
                    "ทำให้ดีที่สุด! 💪",
                    "เป็นไปได้เสมอ! 💯",
                    "ก้าวต่อไป! 🚀",
                    "เชื่อในตัวเอง! ✅",
                    "พรุ่งนี้จะดีขึ้น! 🌈",
                    "อย่าหยุดเดิน! 🏆",
                    "พยายามต่อไป! ❤️",
                    "เธอทำได้! 😊",
                    "ขอให้โชคดี! 🍀"
                  ];

                  // สุ่มข้อความจาก array
                  $randomMessage = $encouragements[array_rand($encouragements)];
                  ?>
                  <div class="col">
                    <h6 id="welcomeMessage"><?php echo number_format($calToDay, 0); ?> ~ <?php echo $randomMessage; ?></h6>
                  </div>
                  <div class="col-auto">
                    <span class="badge badge-light text-bg-theme-1 theme-orange">
                      <span class="bi bi-award text-theme-1"></span> <?php echo session()->get('user')->target; ?>
                    </span>
                  </div>
                <?php } ?>
              </div>
            </div>
            <div class="card-body py-0 z-index-1">
              <div class="height-170 text-center position-relative mb-3">
                <div
                  class="position-absolute top-50 start-50 translate-middle z-index-1 bg-white rounded-circle p-1">
                  <figure
                    class="avatar avatar-120 rounded-circle coverimg align-middle shadow-sm">
                    <img src="<?php echo session()->get('user')->profile; ?>" alt="" />
                  </figure>
                </div>
                <canvas
                  id="doughnutchart"
                  class="position-relative z-index-0 mx-auto"></canvas>
              </div>
              <div class="row mb-4 text-center">
                <?php if (session()->get('user')->cal_per_day) { ?>
                  
                  <div class="row">
                  <div class="col-4 col-lg-4 mb-3">
                      <p class="small">
                        <span
                          class="me-1 avatar avatar-20 rounded bg-blue"
                        ></span>
                        ทาน (แคลอรี่)
                        <!-- <span class="text-success fw-normal ms-1">80%</span> -->
                      </p>
                    </div>
                    <div class="col-4 col-lg-4 mb-3">
                      <p class="small">
                        <span
                          class="me-1 avatar avatar-20 rounded bg-white"
                        ></span>
                        แคลอรี่ที่รับได้
                        <!-- <span class="text-success fw-normal ms-1">10%</span> -->
                      </p>
                    </div>
                    <div class="col-4 col-lg-4 mb-3">
                      <p class="small">
                        <span
                          class="me-1 avatar avatar-20 rounded bg-orange"
                        ></span>
                        เผาผลาญ
                        <!-- <span class="text-success fw-normal ms-1">10%</span> -->
                      </p>
                    </div>
                    <!-- <div class="col-6 col-lg-6 mb-3">
                      <p class="small">
                        <span
                          class="me-1 avatar avatar-20 rounded bg-white"
                        ></span>
                        Other
                        <span class="text-success fw-normal ms-1">10%</span>
                      </p>
                    </div> -->
                  </div>

                <?php } else { ?>

                <?php } ?>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-12">
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
              <a href="<?php echo base_url('/menu'); ?>">
                <div class="card adminuiux-card border-0 height-150 mb-3 overflow-hidden hover-action">
                  <figure class="position-absolute start-0 top-0 w-100 h-100 coverimg z-index-0" style="background-image: url(&quot;assets/img/fitness/image-10.jpg&quot;);">
                    <img src="assets/img/fitness/image-10.jpg" alt="" style="display: none;">
                  </figure>
                  <div class="card-body position-relative">
                    <h4 class="mb-0">การกิน</h4>
                    <p class="opacity-75 mb-4">การกิน, แคลวันนี้</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-6">
              <a href="<?php echo base_url('/workout'); ?>">
                <div class="card adminuiux-card border-0 height-150 mb-3 overflow-hidden hover-action">
                  <figure class="position-absolute start-0 top-0 w-100 h-100 coverimg z-index-0" style="background-image: url(&quot;assets/img/modern-ai-image/user-4.jpg&quot;);">
                    <img src="assets/img/modern-ai-image/user-4.jpg" alt="" style="display: none;">
                  </figure>
                  <div class="card-body position-relative">
                    <h4 class="mb-0">ออกกำลังกาย</h4>
                    <p class="opacity-75 mb-4">การออกกำลังกายวันนี้</p>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-6">
              <a href="<?php echo base_url('/food/table'); ?>">
                <div class="card adminuiux-card border-0 height-150 bg-theme-l-gradient mb-3 hover-action">
                  <div class="card-body position-relative">
                    <h4 class="mb-0">ตารางอาหาร</h4>
                    <p class="opacity-75 mb-4">กำหนดตารางอาชีพจากมืออาชีพโดย <span class="badge badge-light text-bg-theme-1 theme-black">🤖 AI</span></p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
</div>