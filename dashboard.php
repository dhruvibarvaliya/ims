<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("location:index.php");
    exit();
}

$userid = $_SESSION['userid'];
$br_id = $_SESSION['b_id'];
$sql = "SELECT admin.*, branch.id as branch_id, branch.name as branch_name 
        FROM admin 
        JOIN branch ON admin.b_id=branch.id 
        WHERE admin.id=$userid";
$res = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($res);
$branch = $row['branch_name'];

// Fetch all branches
$branches_sql = "SELECT * FROM branch";
$branches_res = mysqli_query($con, $branches_sql);
$branches = [];
while ($branch_row = mysqli_fetch_assoc($branches_res)) {
    $branch_name = $branch_row['name'];
    $branch_id = $branch_row['id'];
    $branch_slug = strtolower(str_replace(' ', '', $branch_name));

    $today = date('Y-m-d');
    // echo $today;die();
    $today_inq= "SELECT COUNT(*) as today_inquiries FROM inquiry WHERE branch = $branch_id AND DATE(i_date) = '$today'";

    $today_inq_res = mysqli_query($con, $today_inq);
    $today_inquiries = mysqli_fetch_assoc($today_inq_res)['today_inquiries'];

      $follow_ups_query = "
        SELECT COUNT(*) as follow_ups 
        FROM followup 
        JOIN inquiry ON followup.inq_id = inquiry.id 
        WHERE inquiry.branch = $branch_id AND DATE(followup.f_date) = '$today'
    ";
    $follow_ups_res = mysqli_query($con, $follow_ups_query);
    $follow_ups = mysqli_fetch_assoc($follow_ups_res)['follow_ups'];
    $branches[] = [
        'name' => $branch_name,
        'id' => $branch_id,
        'slug' => $branch_slug,
        'today_inquiries' => $today_inquiries,
         'follow_ups' => $follow_ups
    ];
}

include("header.php");
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="row faq" id="accordion">
            <?php foreach ($branches as $branch_data): $b_id = $branch_data['id']; ?>
            <div class="col-12 card-primary">
                <div class="card card-primary card-outline">
                    <a class="d-block w-100 " data-toggle="collapse" href="#<?= $branch_data['slug'] ?>">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                <?= strtoupper($branch_data['name']) ?>
                            </h4>
                        </div>
                    </a>
                    <div id="<?= $branch_data['slug'] ?>" class="collapse <?php if($b_id==$br_id) { ?> show <?php } ?>" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3><?= $branch_data['today_inquiries'] ?></h3>
                                            <p>Today Inquiries</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag"></i>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-lg-6 col-6">
                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3><?= $branch_data['follow_ups'] ?></h3>
                                            <p>Today Follow Ups</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-stats-bars"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->

<?php include("footer.php") ?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="dist/js/demo.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>

</body>
</html>
