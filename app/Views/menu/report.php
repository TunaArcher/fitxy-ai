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
<div id="title" class="d-none">แคลอรี่</div>
<div class="adminuiux-wrap">
  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">

      <div class="my-3 text-center">
        <h2 id="totalCalToday"><?php echo number_format($calToDay, 0); ?></h2>
        <p>จำนวนแคลวันนี้</p>
      </div>

      <?php foreach ($userMenusToday as $key => $menu) { ?>
        <div class="col-12 mb-3">

          <div class="card adminuiux-card" data-menu-id="<?php echo $menu->id; ?>">
            <div class="card-body pt-2">
              <!-- ปุ่มปิด -->
              <button class="close-btn position-absolute top-0 end-0 m-2 border-0 bg-transparent">✕</button>
              <div class="row gx-3 align-items-center mb-2" data-bs-toggle="modal" data-bs-target="#standardmodal">
                <div class="col">
                  <h6 class="text-truncated"><?php echo $menu->name; ?></h6>
                </div>
              </div>
              <div class="row gx-3 align-items-center" data-bs-toggle="modal" data-bs-target="#standardmodal">
                <div class="col-4">
                  <figure class="height-50 w-100 rounded coverimg mb-0" style="background-image: url(&quot;<?php echo $menu->content; ?>&quot;);"><img src="<?php echo $menu->content; ?>" alt="" style="display: none;"></figure>
                </div>
                <div class="col-8">
                  <div class="row gx-3">
                    <div class="col">
                      <p class="small mb-0"><?php echo number_format($menu->carbohydrates, 0); ?> g</p>
                      <p class="fs-12 opacity-75">Carbs</p>
                    </div>
                    <div class="col">
                      <p class="small mb-0"><?php echo number_format($menu->protein, 0); ?> g</p>
                      <p class="fs-12 opacity-75">Protein</p>
                    </div>
                    <div class="col">
                      <p class="small mb-0"><?php echo number_format($menu->fat, 0); ?> g</p>
                      <p class="fs-12 opacity-75">Fat</p>
                    </div>
                    <div class="col">
                      <p class="small mb-0 menu-cal"><?php echo number_format($menu->calories, 0); ?> kcal</p>
                      <p class="fs-12 opacity-75">Energy</p>
                    </div>
                  </div>
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

<?php echo view('layouts/bottom_menu'); ?>