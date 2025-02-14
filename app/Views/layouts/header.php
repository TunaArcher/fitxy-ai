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
    <script src="assets/js/app.js"></script>
    <link href="assets/css/app.css" rel="stylesheet" />
    <?php if (isset($css_critical)) {
        echo $css_critical;
    } ?>

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
            background-image: linear-gradient(45deg, #ff0000, #ff7300, #ffeb00, #47ff00, #00ffd5, #002bff, #7a00ff, #ff00c8);
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
        var customer = <?= json_encode(session()->get('customer') ?? null, JSON_UNESCAPED_UNICODE); ?>;
        var calPerDay = <?= session()->get('customer')->cal_per_day; ?>;
        var calToDay = <?= $calToDay; ?>;
    </script>
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
    </div>