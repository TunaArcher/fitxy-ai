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
    <script defer="defer" src="assets/js/app.js?287a058e58b08a2735d5"></script>
    <link href="assets/css/app.css?287a058e58b08a2735d5" rel="stylesheet" />
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
                <div class="col-auto">
                    <div class="dropdown input-group-text border-0 p-0">
                        <button
                            class="dropdown-toggle btn btn-link btn-square no-caret"
                            type="button"
                            id="searchfilter2"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i data-feather="sliders"></i>
                        </button>
                        <div
                            class="dropdown-menu dropdown-menu-end dropdown-dontclose width-300">
                            <ul class="nav adminuiux-nav" id="searchtab2" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link active"
                                        id="searchall-tab2"
                                        data-bs-toggle="tab"
                                        data-bs-target="#searchall2"
                                        type="button"
                                        role="tab"
                                        aria-controls="searchall2"
                                        aria-selected="true">
                                        All
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link"
                                        id="searchorders-tab2"
                                        data-bs-toggle="tab"
                                        data-bs-target="#searchorders2"
                                        type="button"
                                        role="tab"
                                        aria-controls="searchorders2"
                                        aria-selected="false"
                                        tabindex="-1">
                                        Orders
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link"
                                        id="searchcontacts-tab2"
                                        data-bs-toggle="tab"
                                        data-bs-target="#searchcontacts2"
                                        type="button"
                                        role="tab"
                                        aria-controls="searchcontacts2"
                                        aria-selected="false"
                                        tabindex="-1">
                                        Contacts
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content py-3" id="searchtabContent">
                                <div
                                    class="tab-pane fade active show"
                                    id="searchall2"
                                    role="tabpanel"
                                    aria-labelledby="searchall-tab2">
                                    <ul
                                        class="list-group adminuiux-list-group list-group-flush bg-none show">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Search apps</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch1" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch1"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Include Pages</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch2"
                                                            checked="" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch2"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Internet resource</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch3"
                                                            checked="" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch3"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">News and Blogs</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch4" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch4"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div
                                    class="tab-pane fade"
                                    id="searchorders2"
                                    role="tabpanel"
                                    aria-labelledby="searchorders-tab2">
                                    <ul
                                        class="list-group adminuiux-list-group list-group-flush bg-none show">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Show order ID</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch5" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch5"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">International Order</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch6"
                                                            checked="" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch6"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Taxable Product</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch7"
                                                            checked="" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch7"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Published Product</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch8" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch8"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div
                                    class="tab-pane fade"
                                    id="searchcontacts2"
                                    role="tabpanel"
                                    aria-labelledby="searchcontacts-tab2">
                                    <ul
                                        class="list-group adminuiux-list-group list-group-flush bg-none show">
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Have email ID</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch9" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch9"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Have phone number</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch10"
                                                            checked="" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch10"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Photo available</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch11"
                                                            checked="" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch11"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col">Referral</div>
                                                <div class="col-auto">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="searchswitch12" />
                                                        <label
                                                            class="form-check-label"
                                                            for="searchswitch12"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="">
                                <div class="row">
                                    <div class="col">
                                        <button class="btn btn-link">Reset</button>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-theme">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>