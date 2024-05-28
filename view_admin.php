<?php

include 'db.php';
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:index.php");
}
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sel = "SELECT a_image FROM admin WHERE id = $id";
    $res = mysqli_query($con, $sel);
    if ($res) {
        $data = mysqli_fetch_assoc($res);
        if(file_exists('image/admin/'.$data['a_image']) && $data['a_image'] !="") {
            unlink("image/admin/".$data['a_image']);
        }
        $sql = "DELETE FROM admin WHERE id = $id";
        if (mysqli_query($con, $sql)) {
            header("location:view_admin.php");
            exit();
        } else {
             echo '<script>alert("this admin used in another table so you can not delete it.")</script>';
        }
    } else {
        echo "Error fetching image data: " . mysqli_error($con);
    }
}

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$offset = ($page - 1) * $limit;


$sql_p = "SELECT admin.*,role.position,branch.name AS branch_name FROM admin 
          INNER JOIN role ON admin.r_id = role.id 
          INNER JOIN branch ON admin.b_id = branch.id";

$sql_p .= " ORDER BY admin.id ASC";

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
                    <h1>View Admin</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">View Admin</li>
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
                            <h3 class="card-title">View Admin</h3>
                        </div>
                        <div class="text-center mt-3">
                            <form method="get" class="form-inline justify-content-center" id="srchfrm">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by Name" id="search">
                                </div>
                                <input type="hidden" name="page_no" value="1">

                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>name</th>
                                        <th>email</th>
                                        <th>role</th>
                                        <th>branch</th>
                                        <th>image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="ans">
                                    <?php
                                    while ($data = mysqli_fetch_assoc($res_p)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $data['id']; ?></td>
                                            <td><?php echo $data['name']; ?></td>
                                            <td><?php echo $data['email']; ?></td>
                                            <td><?php echo $data['position']; ?></td>
                                            <td><?php echo $data['branch_name']; ?></td>
                                            <td>
                                                <div style="height:70px; width: 70px; overflow:hidden;">
                                                    <img src="image/admin/<?php echo $data['a_image']; ?>" alt="Profile Picture" style="height: 100%; width:100%; object-fit:cover;">
                                                </div>
                                            </td>
                                           <td class="actions">
                                                <?php 
                                                if(isset($_SESSION['userid']) && $_SESSION['userid'] == $data['id']) { 
                                                ?>
                                                    <span class="btn bg-gradient-secondary" disabled>DELETE</span>
                                                <?php } else { ?>
                                                    <a href="view_admin.php?id=<?php echo $data['id']; ?>" class="btn bg-gradient-danger">DELETE</a>
                                                <?php } ?>
                                                <a href="add_admin.php?id=<?php echo $data['id']; ?>" class="btn bg-gradient-primary">EDIT</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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

                    </div>
                </div>
            </div>
        </div>
    </section>
    <h6 id="hello">hello</h6>
</div>

<?php include 'footer.php'; ?>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
    
       $(document).on('click','.page_no',function(){
        var page_no=$(this).attr('page-no');
         var search = $('#search').val();
        $.ajax({
            type:"get",
            url:"admin_ajax.php",
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
                url:"admin_ajax.php",
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
