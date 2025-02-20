<div class="my-3 text-center">
    <h2 id="totalCalToday"><?php echo $caloriesToDay; ?> </h2>
    <p>แคลที่เผาผลาญไป</p>
</div>

<style>
    .coverimg-x {
        width: 70px !important;
    }
</style>
<?php foreach ($userWorkouts as $key => $workout) { ?>
    <div class="col-12 mb-3">
        <div class="card adminuiux-card border-0 bg-gradient-<?php echo ($key + 1); ?> mt-3" data-workout-id="<?php echo $workout->id; ?>">
            <div class="card-body position-relative z-index-1">

                <!-- ปุ่มปิด -->
                <button class="close-btn position-absolute top-0 end-0 m-2 border-0 bg-transparent">
                    ✕
                </button>

                <div class="row">

                    <div class="col-3">
                        <figure class="height-70 w-100 rounded coverimg coverimg-x mb-0">
                            <img src="<?php echo base_url('assets/img/workout/' . $workout->icon); ?>" alt="" />
                        </figure>
                    </div>
                    <div class="col-9">
                        <h6 class="text-truncated">
                            <?php echo $workout->user_workout_title; ?>
                        </h6>
                        <p class="text-secondary fs-14 mb-2">
                            <span class="me-1"><i class="bi bi-clock me-1"></i> <?php echo $workout->time; ?> นาที</span>
                            <span class="me-1"><i class="bi bi-fire me-1"></i> <?php echo $workout->calories; ?> แคลอรี่</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- <div class="adminuiux-mobile-footer hide-on-scrolldown pb-ios">
    <div class="p-3">
        <a href="<?php echo base_url('/workout/add'); ?>" class="btn btn-theme w-100">
            เพิ่มการออกกำลังกาย
        </a>
    </div>
</div> -->


<div class="position-fixed bottom-0 end-0 m-3 z-index-5" id="fixedbuttons" style="padding-bottom: 65px;">
    <a href="<?php echo base_url('/workout/add'); ?>" class="btn btn-lg btn-square btn-theme shadow rounded-circle">
    <i class="bi bi-person-walking"></i>+
    </a>
</div>

<?php echo view('layouts/bottom_menu'); ?>