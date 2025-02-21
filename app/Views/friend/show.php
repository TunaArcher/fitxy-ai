<style>
  /* Animated Gradient for Title */
  .animated-gradient {
    background: linear-gradient(45deg, #ff6a00, #ee0979, #00c6ff, #12d8fa);
    background-size: 300% 300%;
    animation: gradientMove 5s infinite linear;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  @keyframes gradientMove {
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

  /* Rank Badge */
  .rank-badge {
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 8px;
    display: inline-block;
  }

  .rank-1 {
    background: linear-gradient(45deg, #ffd700, #ffb400);
  }

  .rank-2 {
    background: linear-gradient(45deg, #c0c0c0, #b0b0b0);
  }

  .rank-3 {
    background: linear-gradient(45deg, #cd7f32, #a35d24);
  }

  /* กำหนดพื้นหลังให้เป็นสีดำโปร่งแสง */
  .custom-table {
    --bs-table-bg: rgba(255, 255, 255, 0.2) !important;
    /* แก้ค่า table background */
    --bs-table-border-color: rgba(255, 255, 255, 0.2) !important;
    /* สีเส้นขอบ */
    /* กำหนดสีข้อความเป็นขาว */
    border-radius: 10px;
    overflow: hidden;
  }

  /* ปรับพื้นหลังของ header */
  .custom-table thead {
    background: rgba(255, 255, 255, 0.2) !important;
    /* ให้หัวตารางมีสีโปร่งแสง */
  }

  /* ปรับสีของขอบตาราง */
  .custom-table tbody tr {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
  }
</style>
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
<div id="title" class="d-none">เพื่อน</div>
<div class="adminuiux-wrap">
  <main class="adminuiux-content" onclick="contentClick()">
    <div class="container mt-3" id="main-content">

      <h2 class="text-center animated-gradient">🏆 Top Ranking Players</h2>

      <!-- Tabs -->
      <ul class="nav nav-pills nav-justified mt-4">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#lose-weight">🥗 แก๊งลดน้ำหนัก</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="pill" data-bs-target="#gain-weight">🍖 แก๊งเพิ่มน้ำหนัก</button>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content mt-4">

        <!-- 🥗 แก๊งลดน้ำหนัก -->
        <div class="tab-pane fade show active" id="lose-weight">
          <table class="table custom-table text-center">
            <thead>
              <tr>
                <th>ลำดับ</th>
                <th>ชื่อ</th>
                <th>Weight Lost (kg)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><span class="rank-badge rank-1">🥇 1</span></td>
                <td>User 1</td>
                <td>10.2 kg</td>
              </tr>
              <tr>
                <td><span class="rank-badge rank-2">🥈 2</span></td>
                <td>User 2</td>
                <td>8.7 kg</td>
              </tr>
              <tr>
                <td><span class="rank-badge rank-3">🥉 3</span></td>
                <td>User 3</td>
                <td>7.5 kg</td>
              </tr>
              <tr>
                <td>4</td>
                <td>User 4</td>
                <td>6.2 kg</td>
              </tr>
              <tr>
                <td>5</td>
                <td>User 5</td>
                <td>6.2 kg</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- 🍖 แก๊งเพิ่มน้ำหนัก -->
        <div class="tab-pane fade" id="gain-weight">
          <table class="table custom-table text-center">
            <thead>
              <tr>
                <th>ลำดับ</th>
                <th>ชื่อ</th>
                <th>Weight Gained (kg)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><span class="rank-badge rank-1">🥇 1</span></td>
                <td>User 1</td>
                <td>6.5 kg</td>
              </tr>
              <tr>
                <td><span class="rank-badge rank-2">🥈 2</span></td>
                <td>User 2</td>
                <td>5.8 kg</td>
              </tr>
              <tr>
                <td><span class="rank-badge rank-3">🥉 3</span></td>
                <td>User 3</td>
                <td>4.9 kg</td>
              </tr>
              <tr>
                <td>4</td>
                <td>User 4</td>
                <td>4.3 kg</td>
              </tr>
              <tr>
                <td>5</td>
                <td>User 5</td>
                <td>4.3 kg</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      <hr>

      <div class="swiper swipernav mb-3">
        <div class="swiper-wrapper">

          <?php foreach ($friends as $key => $friend) { ?>
            <div class="swiper-slide width-400">
              <div class="card adminuiux-card border-0 bg-gradient-<?php echo ($key + 1); ?>">
                <div class="card-body">
                  <div class="row">
                    <div class="col-4"><a href="#">
                        <figure class="w-100 height-140 rounded coverimg" style="background-image: url(<?php echo $friend->profile; ?>);"><img src="<?php echo $friend->profile; ?>" alt="" style="display: none;"></figure>
                      </a></div>
                    <div class="col-8">
                      <div class="row gx-3 align-items-center mb-2">
                        <div class="col">
                          <p><span class="badge badge-sm badge-light text-bg-theme-1 theme-cyan"><i class="bi bi-patch-check-fill"></i> <?php echo $friend->target; ?></span></p>
                        </div>
                      </div>
                      <p class="mb-0"><a href="#" class="style-none"><?php echo $friend->name; ?></a></p>
                      <p class="small text-secondary mb-2"><?php echo 'น้ำหนัก: ' . $friend->weight; ?></p>
                      <div class="row gx-3 align-items-center">
                        <div class="col">
                          <p class="text-theme-1 theme-yellow mb-0 disabled"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i></p>
                        </div>
                        <div class="col-auto">
                          <a href="tel:#" class="disabled btn btn-sm btn-square btn-theme theme-green"><i class="bi bi-telephone"></i></a>
                          <button class="disabled btn btn-sm btn-square btn-theme mx-1 theme-yellow" data-bs-toggle="modal" data-bs-target="#chatmodal"><i class="bi bi-chat-left-text"></i></button>
                          <button class="disabled btn btn-sm btn-square btn-theme" data-bs-toggle="modal" data-bs-target="#addappointment"><i class="bi bi-calendar-event"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>


        </div>
      </div>

    </div>
  </main>
</div>

<?php echo view('layouts/bottom_menu'); ?>