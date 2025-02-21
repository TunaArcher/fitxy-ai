<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>FitXy-AI | APP</title>
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="ฟิตสิ- แอปพลิเคชันเพื่อคนรักสุขภาพที่ดีที่สุด">
  <meta property="og:description" content="ร่วมออกกำลังกายกับเพื่อน ๆ และค้นหาคลับออกกำลังกายที่ใช่สำหรับคุณ">
  <!-- <meta property="og:image" content="<?php echo base_url('/assets/images/preview.jpg'); ?>"> -->
  <meta property="og:url" content="<?php echo current_url(); ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="ฟิตสิเพื่อน">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="ฟิตสิเพื่อน - แอปพลิเคชันฟิตเนสที่ดีที่สุด">
  <meta name="twitter:description" content="ร่วมออกกำลังกายกับเพื่อน ๆ และค้นหาคลับออกกำลังกายที่ใช่สำหรับคุณ">
  <meta name="twitter:image" content="<?php echo base_url('/assets/images/preview.jpg'); ?>">
  <link rel="shortcut icon" href="<?php echo base_url('/assets/images/logo72x72.png'); ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&family=SUSE:wght@100..800&display=swap"
    rel="stylesheet" />
  <style>
    :root {
      /* --adminuiux-content-font: "Open Sans", sans-serif; */
      /* --adminuiux-content-font-weight: 400; */
      /* --adminuiux-title-font: "SUSE", sans-serif; */
      /* --adminuiux-title-font-weight: 600; */
    }
  </style>
  <script src="<?php echo base_url('assets/js/app.js'); ?>"></script>
  <link href="<?php echo base_url('assets/css/app.css'); ?>" rel="stylesheet" />


  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <!-- เรียกใช้ Google Translate Element -->
  <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <style>
    /** BASE **/
    * {
      font-family: 'Kanit', sans-serif;
    }

    .disabled {
      opacity: 0.6;
      cursor: not-allowed;
      pointer-events: none;
    }

    .text-gd {
      font-weight: bold;
      text-transform: uppercase;
      background-image: linear-gradient(45deg, #e46dce, #c763d2, #8b5fd9, #4d77e6, #03aed2, #03d2b5);
      background-size: 300% 300%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: animateGradient 4s linear infinite;
    }

    @keyframes animateGradient {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }
  </style>
  <script>
    var serverUrl = '<?php echo base_url(); ?>';
  </script>
  <script>
    function goBackHome() {
      // เปลี่ยน URL ใน window.location.href ให้ตรงกับหน้าแรกของเว็บไซต์คุณ
      window.location.href = '/';
    }
  </script>
  <style>
    .pageloader {
      position: fixed;
      /* ทำให้เต็มหน้าจอ */
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: #ffffff;
      /* สามารถเปลี่ยนเป็นสีโปร่งใสหรือตามที่ต้องการ */
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      /* ให้แน่ใจว่าอยู่บนสุด */
    }

    .pageloader img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* ปรับขนาดให้เต็มจอโดยไม่เสียอัตราส่วน */
    }

    .fixedbuttons {
      padding-bottom: 65px !important;
    }
  </style>
  <style>
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

    .gradient-badge {
      background: linear-gradient(45deg, #6a11cb, #2575fc, #6a11cb);
      background-size: 400% 400%;
      animation: gradient-animation 5s ease infinite;
      color: #fff;
      border: none;
      /* padding: 0.25em 0.5em; */
      /* font-size: 0.8rem; */
    }

    @keyframes gradient-animation {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }
  </style>
</head>

<body
  class="main-bg main-bg-opac roundedui adminuiux-header-standard theme-orange adminuiux-header-transparent adminuiux-sidebar-fill-theme bg-white scrollup theme-cyan bg-gradient-10 adminuiux-sidebar-standard"
  data-theme="theme-orange"
  data-sidebarfill="adminuiux-sidebar-fill-theme"
  data-bs-spy="scroll"
  data-bs-target="#list-example"
  data-bs-smooth-scroll="true"
  tabindex="0"
  data-sidebarlayout="adminuiux-sidebar-standard"
  data-headerlayout="adminuiux-header-standard"
  data-headerfill="adminuiux-header-transparent">
  <!-- 
    <div class="pageloader">
        <div class="container h-100">
            <div
                class="row justify-content-center align-items-center text-center h-100 pb-ios">
                <div class="col-12 mb-auto pt-4"></div>
                <div class="col-auto">
                    <img src="<?php echo base_url('assets/img/logo72x72.png'); ?>" alt="" class="height-80 mb-3" />
                    <p class="h2 mb-0 text-theme-accent-1">UnityX</p>
                    <p class="display-3 text-theme-1 fw-bold mb-4">Fitness</p>
                    <div class="loader3 mb-2 mx-auto"></div>
                </div>
                <div class="col-12 mt-auto pb-4">
                    <p class="small text-secondary">
                        Please wait we are preparing awesome things...
                    </p>
                </div>
            </div>
        </div>
    </div> -->

  <div class="pageloader">
    <img src="<?php echo base_url('/assets/img/splash_screen.gif'); ?>">
  </div>
  <header class="adminuiux-header">

  </header>
  <div class="adminuiux-wrap">
    <div class="adminuiux-sidebar shadow-sm">
      <div class="adminuiux-sidebar-inner">
        <ul class="nav flex-column menu-active-line mt-3">
          <li class="nav-item">
            <a href="fitness-dashboard.html" class="nav-link"><i class="menu-icon bi bi-columns-gap"></i>
              <span class="menu-name">Dashboard</span></a>
          </li>
          <li class="nav-item">
            <a href="fitness-diet-all.html" class="nav-link"><svg
                xmlns="http://www.w3.org/2000/svg"
                class="menu-icon"
                viewBox="0 0 19.679 18.075">
                <g id="meal-icon" transform="translate(-125.935 -162.02)">
                  <path
                    id="Path_42"
                    data-name="Path 42"
                    d="M3755.531,2500.078a2.477,2.477,0,0,1,2.329-2.3c2.31-.352,2.858-.352,3.6-1.331s2.232-2.78,4.268-1.605,1.879,3.093,3.446,3.367a2.323,2.323,0,0,1,1.762,2"
                    transform="translate(-3628 -2329)"
                    fill="none"
                    stroke=""
                    stroke-width="1" />
                  <path
                    id="Path_43"
                    data-name="Path 43"
                    d="M3767.218,2496.091c.744-.862,3.093-3.6,3.093-3.6s1.1-1.488,1.958-.744-.392,1.958-.392,1.958L3768,2497.07"
                    transform="translate(-3628 -2329)"
                    fill="none"
                    stroke=""
                    stroke-width="1" />
                  <path
                    id="Path_45"
                    data-name="Path 45"
                    d="M145.615,171.544H126.988s1.28,8.143,8.882,8.051,8.871-7.74,8.871-7.74"
                    fill="none"
                    stroke=""
                    stroke-width="1" />
                  <path
                    id="Path_46"
                    data-name="Path 46"
                    d="M3757.935,2506.125h12.049"
                    transform="translate(-3628 -2329)"
                    fill="none"
                    stroke=""
                    stroke-width="1" />
                  <path
                    id="Path_47"
                    data-name="Path 47"
                    d="M3757.935,2506.125h12.049"
                    transform="translate(-3632 -2334.582)"
                    fill="none"
                    stroke=""
                    stroke-width="1" />
                </g>
              </svg>
              <span class="menu-name">Diet Plans</span></a>
          </li>
          <li class="nav-item dropdown">
            <a
              href="javascrit:void(0)"
              class="nav-link dropdown-toggle"
              data-bs-toggle="dropdown"><i class="menu-icon bi bi-view-list"></i>
              <span class="menu-name">Fitness Club</span></a>
            <div class="dropdown-menu">
              <div class="nav-item">
                <a href="fitness-club-all.html" class="nav-link"><i class="menu-icon bi bi-building"></i>
                  <span class="menu-name">Clubs</span></a>
              </div>
              <div class="nav-item">
                <a href="fitness-club.html" class="nav-link"><i class="menu-icon bi bi-journal-text"></i>
                  <span class="menu-name">Club Details</span></a>
              </div>
              <div class="nav-item">
                <a href="fitness-club-trainers.html" class="nav-link"><i class="menu-icon bi bi-person-badge"></i>
                  <span class="menu-name">Trainers</span></a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a href="fitness-calendar.html" class="nav-link"><i class="menu-icon bi bi-calendar2-range"></i>
              <span class="menu-name">Calendar</span></a>
          </li>
          <li class="nav-item">
            <a href="fitness-chat.html" class="nav-link"><i class="menu-icon bi bi-chat-dots"></i>
              <span class="menu-name">Chat</span></a>
          </li>
          <li class="nav-item">
            <a href="fitness-referral.html" class="nav-link"><i class="menu-icon bi bi-people"></i>
              <span class="menu-name">Referral</span></a>
          </li>
          <li class="nav-item">
            <a href="fitness-pages.html" class="nav-link"><i class="menu-icon bi bi-layers"></i>
              <span class="menu-name">Pages</span>
              <span class="badge text-bg-primary mx-2">40+</span></a>
          </li>
          <li class="nav-item">
            <a href="fitness-personalization.html" class="nav-link"><i class="menu-icon bi bi-palette h4"></i>
              <span class="menu-name">Personalize ❤️</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="components.html"><i class="menu-icon bi bi-cpu"></i>
              <span class="menu-name">Components</span></a>
          </li>
          <li class="nav-item">
            <a href="fitness-settings.html" class="nav-link"><i class="menu-icon" data-feather="settings"></i>
              <span class="menu-name">Settings</span></a>
          </li>
        </ul>
        <div class="mt-auto"></div>
        <ul class="nav flex-column menu-active-line mb-2">
          <li class="nav-item">
            <a href="fitness-chat-and-call.html" class="nav-link">
              <div class="col-auto">
                <div
                  class="avatar avatar-30 coverimg rounded d-block align-top">
                  <img src="assets/img/modern-ai-image/user-5.jpg" alt="" />
                </div>
              </div>
              <div class="col px-2 menu-name text-start not-iconic">
                <p class="mb-0 fs-14 lh-20">
                  Alice Johanson<br /><small class="opacity-50">Trainer</small>
                </p>
              </div>
              <div class="col-auto not-iconic">
                <i class="bi bi-chat-dots"></i>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <main class="adminuiux-content has-sidebar" onclick="contentClick()">
      <div class="container-fluid mt-2" id="main-content">
        <div
          class="card adminuiux-card border-0 height-dynamic position-relative overflow-hidden"
          style="--h-dynamic: calc(100vh - 20px)">
          <figure
            class="m-0 position-absolute top-0 start-0 h-100 w-100 coverimg opacity-25">
            <img src="<?php echo $user->profile; ?>" alt="" />
          </figure>
          <div class="card-body z-index-1">
            <div
              class="row align-items-center justify-content-center text-center text-white h-100">
              <div class="col-auto">
                <!-- <h1 class="fw-bold" style="font-size: 100px">
                    4<i class="bi bi-exclamation-circle mx-2"></i>4
                  </h1> -->
                <h1 class="fw-bold mb-0">FitXy-AI! (ฟิตสิ!)</h1>
                <h4 class=""><?php echo $user->name; ?></h4>
                <p class="small opacity-75">
                  ส่งคำเชิญ<br />เพื่อร่วมรักษาสุขภาพไปด้วยกัน
                </p>
                <a href="<?php echo base_url('/friends/ok/' . $user->id); ?>" class="btn btn-light gradient-badge">OK ร่วมฟิต 🚀</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

</body>

</html>