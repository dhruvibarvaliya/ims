<?php
include 'db.php';
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:index.php");
    exit();
}
if(isset($_GET['f_id'])) {
    $id = $_GET['f_id'];
    $sql = "DELETE FROM followup WHERE f_id = $id";
        if (mysqli_query($con, $sql)) {
            header("location:view_follow.php?id=$id");
            exit();
        } else {
             echo '<script>alert("this followup used in another table so you can not delete it.")</script>';
        }
    }

$limit = 10; 
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$sql_p = "SELECT followup.*, admin.name FROM followup INNER JOIN admin ON followup.f_by = admin.id";
if (!empty($id)) {
    $sql_p .= " WHERE followup.inq_id = $id";
}

if (!empty($search)) {
    $sql_p .= " WHERE admin.name LIKE '%$search%'";
}

$sql_p .= " ORDER BY followup.f_id DESC LIMIT $limit OFFSET $offset";

$res_p = mysqli_query($con, $sql_p);

$total_records = mysqli_num_rows($res_p);

$total_pages = ceil($total_records / $limit);
?>

<?php include "header.php"; ?>
<!-- Font Awesome -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View followup</h1>
                   
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">View followup</li>
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
                            <h3 class="card-title">View followup</h3>
                        </div>
                        <div class="text-center mt-3">
                            <form method="GET" class="form-inline justify-content-center">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by Follow up by" value="<?php echo $search; ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-dark">Search</button>
                                    </div>

                                </div>
                                <div class="text-center" style="margin-left:30px;">
                                    <a href="add_follow.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary">Add Followup</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Follow up Reason</th>
                                        <th>joindate</th>
                                        <th>Follow Up By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = mysqli_fetch_assoc($res_p)) { ?>
                                        <tr>
                                            <td><?php echo $data['f_id']; ?></td>
                                            <td><?php echo $data['f_reason']; ?></td>
                                            <td><?php echo $data['joindate']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td class="actions">
                                                <a href="view_follow.php?f_id=<?php echo $data['f_id']; ?>" class="btn bg-gradient-danger">DELETE</a>
                                                <a href="edit_follow.php?f_id=<?php echo $data['f_id']; ?>&id=<?php echo $_GET['id']; ?>" class="btn bg-gradient-primary">EDIT</a>
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
                                echo "<li class='page-item " . ($page <= 1 ? 'disabled' : '') . "'><a class='page-link' href='view_followup.php?page=$prev_page&search=$search'>Previous</a></li>";

                                for ($i = 1; $i <= $total_pages; $i++) {
                                    echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='view_followup.php?page=$i&search=$search'>$i</a></li>";
                                }

                                $next_page = $page + 1;
                                echo "<li class='page-item " . ($page >= $total_pages ? 'disabled' : '') . "'><a class='page-link' href='view_followup.php?page=$next_page&search=$search'>Next</a></li>";
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

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- Page specific script -->
</body>

</html>
