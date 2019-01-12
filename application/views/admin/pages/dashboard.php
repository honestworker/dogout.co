<?php
$admin_counts = $user_counts = 0;
if (isset($users)) {
    if (isset($users['admins'])) {
        $admin_counts = $users['admins'];
    }
    if (isset($users['users'])) {
        $user_counts = $users['users'];
    }
}
$app_reviews_counts = $app_reviews_total = $non_dogfriendlys_counts = $non_dogfriendlys_total = $new_locations_counts = $new_locations_total = 0;
if (isset($reports)) {
    if (isset($reports['app_reviews_counts'])) {
        $app_reviews_counts = $reports['app_reviews_counts'];
    }
    if (isset($reports['app_reviews_total'])) {
        $app_reviews_total = $reports['app_reviews_total'];
    }
    if (isset($reports['non_dogfriendlys_counts'])) {
        $non_dogfriendlys_counts = $reports['non_dogfriendlys_counts'];
    }
    if (isset($reports['non_dogfriendlys_total'])) {
        $non_dogfriendlys_total = $reports['non_dogfriendlys_total'];
    }
    if (isset($reports['new_locations_counts'])) {
        $new_locations_counts = $reports['new_locations_counts'];
    }
    if (isset($reports['new_locations_total'])) {
        $new_locations_total = $reports['new_locations_total'];
    }
}
?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo $admin_counts;?></h3>
                    <p>Administrators</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="<?php echo (base_url() . 'admins'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?php echo $user_counts;?></h3>
                    <p>Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-people"></i>
                </div>
                <a href="<?php echo (base_url() . 'users'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-6 col-xs-12">
            <div class="row">
                <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?php echo $app_reviews_counts;?>,&nbsp;<?php echo $app_reviews_total;?></h3>
                            <p>App Reviews</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-star"></i>
                        </div>
                        <a href="<?php echo (base_url() . 'appreviews'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><?php echo $non_dogfriendlys_counts;?>,&nbsp;<?php echo $non_dogfriendlys_total;?></h3>
                            <p>Non Dog-Friendlys</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-frown-o"></i>
                        </div>
                        <a href="<?php echo (base_url() . 'nondogfriendlys'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3><?php echo $new_locations_counts;?>,&nbsp;<?php echo $new_locations_total;?></h3>
                            <p>New Locations</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <a href="<?php echo (base_url() . 'newlocations'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->