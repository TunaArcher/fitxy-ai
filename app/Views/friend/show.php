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
<div id="title" class="d-none">โปรไฟล์</div>
<div class="adminuiux-wrap">
  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">
      <div class="card adminuiux-card border-0 overflow-hidden mb-3">
        <figure
          class="h-100 w-100 coverimg blur-overlay position-absolute start-0 top-0 z-index-0 opacity-50">
          <img src="assets/img/fitness/image-1.jpg" alt="" />
        </figure>
        <div class="card-body text-center z-index-1">
          <div
            class="avatar avatar-110 border rounded-circle mx-auto border-theme-1 mb-3">
            <figure class="avatar avatar-100 coverimg rounded-circle">
              <img src="<?php echo session()->get('user')->profile; ?>" alt="" />
            </figure>
          </div>
          <h5 class="mb-0"><?php echo session()->get('user')->name; ?></h5>
          <p class="small opacity-75">เป้าหมาย <?php echo session()->get('user')->target; ?></p>
        </div>
      </div>
      <div class="row gx-3">
        <div class="col-12 col-md-8 col-lg-6 col-xxl-5 mb-3">
          <p class="mb-0">เชิญเพื่อนมารักษาสุขภาพด้วยกัน</p>
          <p class="small text-secondary">Copy &amp; Share referral link with your network</p>
          <div class="input-group"><input class="form-control border-theme-1" placeholder="Referral Code" aria-describedby="button-addon2" value="<?php echo urldecode(base_url('/lets/go/' . session()->get('user')->id . '/ฟิตสิเพื่อน')); ?>
" disabled="disabled"> <button class="btn btn-outline-theme" type="button" id="button-addon2" data-bs-toggle="tooltip" aria-label="Copy" data-bs-original-title="Copy"><i class="bi bi-copy"></i></button></div>
        </div>
      </div>
      <div class="list-group adminuiux-list-group">
        <a
          class="list-group-item list-group-item-action"
          href="<?php echo base_url('/friends/' . session()->get('user')->id); ?>">
          <div class="row gx-0">
            <div class="col align-self-center">
              <i data-feather="layout" class="avatar avatar-18 me-1"></i> เพื่อนของฉัน
            </div>
            <?php
// กำหนดจำนวนที่จะแสดงก่อนขึ้น "+x"
$maxDisplay = 4;
$extraCount = count($friends) - $maxDisplay;
?>
            <div class="col-auto avatar-group">
    <?php foreach (array_slice($friends, 0, $maxDisplay) as $friend): ?>
        <figure class="avatar avatar-20 coverimg rounded-circle">
            <img src="<?= htmlspecialchars($friend->profile) ?>" alt="User <?= $friend->id; ?>" />
        </figure>
    <?php endforeach; ?>

    <?php if ($extraCount > 0): ?>
        <div class="avatar avatar-20 bg-theme-1 rounded-circle text-center align-middle">
            <small class="fs-10 align-middle"><?= $extraCount ?>+</small>
        </div>
    <?php endif; ?>
</div>
          </div>
        </a><a
          class="list-group-item list-group-item-action disabled"
          href="#"><i data-feather="dollar-sign" class="avatar avatar-18 me-1"></i>
          Earning </a><a
          class="list-group-item list-group-item-action disabled"
          href="#">
          <div class="row">
            <div class="col">
              <i data-feather="gift" class="avatar avatar-18 me-1"></i>
              Subscription
            </div>
            <div class="col-auto">
              <p class="small text-success">Upgrade</p>
            </div>
            <div class="col-auto">
              <span class="arrow bi bi-chevron-right"></span>
            </div>
          </div>
        </a><a
          class="list-group-item list-group-item-action disabled"
          href="fitness-settings.html"><i data-feather="settings" class="avatar avatar-18 me-1"></i>
          ตั้งค่า</a>
      </div>
    </div>
  </main>
</div>

<?php echo view('layouts/bottom_menu'); ?>