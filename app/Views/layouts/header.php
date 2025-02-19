<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>APP</title>
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

        var userGender = '<?= session()->get('user')->gender ?: '""'; ?>';
        var userAge = <?= session()->get('user')->age ?: '""'; ?>;
        var userWeight = <?= session()->get('user')->weight ?: '""'; ?>;
        var userHeight = <?= session()->get('user')->height ?: '""'; ?>;
        var userExercise = '<?= session()->get('user')->exercise ?: '""'; ?>';
        var userTarget = '<?= session()->get('user')->target ?: '""'; ?>';

        var maintenanceCal = <?= session()->get('user')->maintenanceCal ?: '""'; ?>;
        var calPerDay = <?= session()->get('user')->cal_per_day ?: '""'; ?>;
        
        var calToDay = <?= $calToDay ?: 0; ?>;
        var calBurn = <?= $calBurn ?: 0; ?>;
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
        <img src="https://cdn.discordapp.com/attachments/1089056066530197515/1341019279897919488/1739794300292.gif?ex=67b47907&amp;is=67b32787&amp;hm=08f43dd1533440ca0512b3b4dcdbfbf92b4d35ed57e0aff17d70d599e1435358&amp;">
    </div>