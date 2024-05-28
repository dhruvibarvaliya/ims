<?php
include 'db.php';
session_start();

date_default_timezone_set('Asia/Kolkata');
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$offset = ($page - 1) * $limit;

$search = isset($_GET['name']) ? $_GET['name'] : ''; 
$user_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

$sql_p = "SELECT admin.id, admin.name,detail.d_id,detail.login, detail.logout FROM admin JOIN detail ON admin.id = detail.id  WHERE admin.id = $user_id";
if ($search != '') {
    $sql_p .= " WHERE admin.name LIKE '%$search%'";
}


$res_p = mysqli_query($con, $sql_p);

$total_records = mysqli_num_rows($res_p);

$total_pages = ceil($total_records / $limit);
$sql_p .= " LIMIT $limit OFFSET $offset";
$res_p = mysqli_query($con, $sql_p);
?>

<?php include "header.php"; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Login Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Login Details</li>
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
                            <h3 class="card-title">Login Details</h3>
                        </div>
                        <div class="text-center mt-3">
                            <form method="GET" class="form-inline justify-content-center">
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control" placeholder="Search by Name" value="<?php echo $search; ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-dark">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Login Time</th>
                                            <th>Logout Time</th>
                                            <th>Working Time</th>
                                    </tr>
                                </thead>
                                 <tbody>
                                        <?php
                                     
                                        while($row = mysqli_fetch_assoc($res_p)){ 
                                          ?>
                                          <tr>
                                            <td><?php echo $row['d_id']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['login']; ?></td>
                                            <td><?php echo $row['logout']; ?></td>
                                            <td>
                                              <?php 
                                                if($row['logout'] !== '') {
                                                    $login_time = strtotime($row['login']);
                                                    $logout_time = strtotime($row['logout']);
                                                    $working_time = $logout_time - $login_time;
                                                    echo gmdate("H:i:s", $working_time);
                                                } else {
                                                    echo "User still logged in";
                                                }
                                              ?>
                                            </td>
                                          </tr>
                                        <?php } ?>
                                      </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <?php
                              $prev_page = $page - 1;
                                echo "<li class='page-item " . ($page <= 1 ? 'disabled' : '') . "'><a class='page-link' href='detail.php?page=$prev_page&name=$search'>Previous</a></li>";

                                for ($i = 1; $i <= $total_pages; $i++) {
                                    echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='detail.php?page=$i&name=$search'>$i</a></li>";
                                }

                               $next_page = $page + 1;
                                echo "<li class='page-item " . ($page >= $total_pages ? 'disabled' : '') . "'><a class='page-link' href='detail.php?page=$next_page&name=$search'>Next</a></li>";

                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
</body>

</html>