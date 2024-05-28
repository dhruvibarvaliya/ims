<?php 
session_start();
include "db.php";
if (!isset($_SESSION['userid'])) {
    header("location:index.php");
}
$userid = $_SESSION['userid'];
$sql_user = "SELECT r_id, b_id FROM admin WHERE id = $userid";
$result_user = mysqli_query($con, $sql_user);
$user = mysqli_fetch_assoc($result_user);
$user_role = $user['r_id'];
$user_branch = $user['b_id'];
include "header.php";

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Faculty Inquiry Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Faculty Inquiry Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Faculty Name</th>
                                        <th>Total Inquiries</th>
                                        <th>Admissions</th>
                                        <th>Pending</th>
                                        <th>Declined</th>
                                        <th>Ratio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $sql_base = "SELECT admin.name AS faculty_name,COUNT(inquiry.id) AS total_inquiries,SUM(status.status = 'admission') AS Admissions,SUM(status.status = 'pending') AS Pending,SUM(status.status = 'declined') AS Declined FROM admin LEFT JOIN inquiry ON inquiry.inquiryby = admin.id LEFT JOIN status ON inquiry.status = status.id";
                                         if ($user_role == 2) {
                                        $sql_i = $sql_base . " GROUP BY admin.id";
                                        } else {
                                        $sql_i = $sql_base . " WHERE inquiry.branch = $user_branch GROUP BY admin.id";
                                         }

                                    $res_i = mysqli_query($con, $sql_i);
                                    
                                        while ($row_i = mysqli_fetch_assoc($res_i)) { ?>
                                            <tr>
                                                <td><?php echo $row_i['faculty_name']; ?></td>
                                                <td><?php echo $row_i['total_inquiries']; ?></td>
                                                <td><?php echo $row_i['Admissions']; ?></td>
                                                <td><?php echo $row_i['Pending']; ?></td>
                                                <td><?php echo $row_i['Declined']; ?></td>
                                                <td>
                                                    <?php 
                                                    $total=$row_i['total_inquiries'];
                                                    $success=$row_i['Admissions'];
                                                    if($total!=0){
                                                        $ratio=($success/$total)*100;
                                                        echo round($ratio,2)."%";
                                                    }else{
                                                        echo "N/A";
                                                    }
                                                     ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include "footer.php"; ?>
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
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>