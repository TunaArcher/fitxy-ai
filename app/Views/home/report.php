<header class="adminuiux-header">
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <button class="btn btn-square btn-link" onclick="goBack()">
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
<div id="title" class="d-none">แคลอรี่</div>
<div class="adminuiux-wrap">
  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">

      <div class="my-3 text-center">
        <h2 id="totalCalToday"><?php echo number_format($calToDay, 0); ?></h2>
        <p>จำนวนแคลวันนี้</p>
      </div>

      <?php foreach ($menuToday as $key => $menu) { ?>
        <div class="col-12 mb-3">
          <div class="card adminuiux-card border-0 mb-3 overflow-hidden hover-action" data-menu-id="<?php echo $menu->id; ?>">

            <figure class="position-absolute start-0 top-0 w-100 h-100 coverimg blur-overlay z-index-0" style="background-image: url(&quot;<?php echo $menu->content; ?>&quot;);"><img src="<?php echo $menu->content; ?>" alt="" style="display: none;"></figure>

            <div class="card-body position-relative z-index-1">

              <!-- ปุ่มปิด -->
              <button class="close-btn position-absolute top-0 end-0 m-2 border-0 bg-transparent">
                ✕
              </button>

              <div class="row gx-3" data-bs-toggle="modal" data-bs-target="#standardmodal">
                <div class="col-4">
                  <a href="#" class="w-100 height-90 rounded coverimg d-inline-block align-top" style="background-image: url(&quot;assets/img/fitness/image-10.jpg&quot;);"><img src="<?php echo $menu->content; ?>" alt="" style="display: none;"></a>
                </div>
                <div class="col-8 d-flex align-items-center justify-content-between">
                  <h4 class="text-theme-1 mb-0"><span style="font-size: 1.2rem;" class="badge badge-light text-bg-theme-<?php echo $key; ?> theme-orange"><?php echo number_format($menu->cal, 0); ?> แคลอรี่</span></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>

    </div>

    <!-- Standard Modal -->
    <div class="modal fade" id="standardmodal" tabindex="-1" aria-labelledby="standardmodalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <p class="modal-title h5" id="standardmodalLabel">แก้ไขแคลอรี่</p>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <input type="text" class="form-control" id="txtCal" placeholder="ระบุ">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-theme w-100" id="btnUpdate">อัพเดท</button>
          </div>
        </div>
      </div>
    </div>

  </main>
</div>