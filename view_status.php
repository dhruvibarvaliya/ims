<?php 
include 'db.php';
session_start();
if(!isset($_SESSION['userid']))
   {
    header("location:index.php");
   }
$search = ''; 
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM status WHERE id = $id";
        if (mysqli_query($con, $sql)) {
            header("location:view_status.php");
            exit();
        } else {
             echo '<script>alert("this status used in another table so you can not delete it.")</script>';
        }
    }

$sql_p = "SELECT * FROM `status`";
$res_p = mysqli_query($con, $sql_p);
$limit = 5;

if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$start = ($page - 1) * $limit;


    $sql_page = "SELECT * FROM status LIMIT $start, $limit";
    $sql1 = "SELECT * FROM status";

$total_rec = mysqli_query($con, $sql1);
$total_r = mysqli_num_rows($total_rec);
$total_pages = ceil($total_r/$limit);
$res_page = mysqli_query($con, $sql_page);
?>

<?php 
include "header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View status</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">View status</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">View status</h3>
                        </div>
                       <div class="text-center mt-3">
                           <form method="GET" class="form-inline justify-content-center" id="srchfrm">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by position" id="search">
                                </div>
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="ans">
                                    <?php 
                                    while($data=mysqli_fetch_assoc($res_page)){ ?>
                                    <tr>
                                        <td><?php echo $data['id']; ?></td>
                                        <td><?php echo $data['status']; ?></td>
                                        
                                        
                                        <td class="actions">
                                                     <a href="view_status.php?id=<?php echo $data['id']; ?>" class="btn bg-gradient-primary">   DELETE</a>
                                                       <a href="add_status.php?id=<?php echo $data['id']; ?>" class="btn bg-gradient-danger">EDIT</a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                     <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right" id="page">
                                <?php
                                    if ($page > 1) {
                                        echo '<li class="page-item"><a class="page-link page_no" href="javascript:void(0)" page-no="' . ($page - 1) . '">Previous</a></li>';
                                    } else {
                                        echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                                    }
                                    
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        if ($i == $page) {
                                            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link page_no" href="javascript:void(0)" page-no="' . $i . '">' . $i . '</a></li>';
                                        }
                                    }

                                    if ($page < $total_pages) {
                                        echo '<li class="page-item"><a class="page-link page_no" href="javascript:void(0)" page-no="' . ($page + 1) . '">Next</a></li>';
                                    } else {
                                        echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                                    }
                                ?>
                            </ul>
                        </div>

                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php 
include 'footer.php';
?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

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
<script>
    
       $(document).on('click','.page_no',function(){
        var page_no=$(this).attr('page-no');
         var search = $('#search').val();
        $.ajax({
            type:"get",
            url:"status_ajax.php",
            data:{'page':page_no,'search':search},
            success:function(res) {
                res=JSON.parse(res);
                $('#ans').html(res.tbl_data);
                $('#page').html(res.page_no);
            }
        })
       })

       $(document).on('keyup','#srchfrm',function(){
            var frm_data = $('#srchfrm').serialize();
            $.ajax({
                type:"get",
                url:"status_ajax.php",
                data:frm_data,
                success:function(res) {
                    res=JSON.parse(res);
                    $('#ans').html(res.tbl_data);
                    $('#page').html(res.page_no);
                }
            })
       })

</script>
</body>
</html>