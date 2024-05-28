<?php

include 'db.php';
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:index.php");
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM inquiry WHERE id = $id";
        if (mysqli_query($con, $sql)) {
            header("location:view_inquiry.php?id=$id");
            exit();
        } else {
             echo '<script>alert("this inquiry used in another table so you can not delete it.")</script>';
        }
    }
    
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$offset = ($page - 1) * $limit;
$roleid = $_SESSION['roleid'];
$userid = $_SESSION['userid'];
$sql_p = "SELECT inquiry.*, branch.name as branch_name, course.course, reference.reference,admin.name as a_name, status.status from inquiry 
          inner join branch on inquiry.branch=branch.id 
          inner join course on inquiry.course_id=course.id 
          inner join reference on inquiry.reference=reference.id 
          inner join admin on inquiry.inquiryby=admin.id 
          inner join status on inquiry.status=status.id";
if ($roleid != 2) {
    $sql_p .= " WHERE inquiry.branch = (SELECT b_id FROM admin WHERE id = $userid)";
}
$sql_p .= " ORDER BY inquiry.id DESC";
$res_p = mysqli_query($con, $sql_p);
$total_records = mysqli_num_rows($res_p);
$total_pages = ceil($total_records / $limit);
$sql_p .= " LIMIT $limit OFFSET $offset";
$res_p = mysqli_query($con, $sql_p);

?>
<?php 
include "header.php";
?>

 <style>
        .blue { color: blue; }
        .red { color: red; }
        .green { color: green; }
    </style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>View Inquiry</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">View Inquiry</li>
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
                            <h3 class="card-title">View Inquiry</h3>
                        </div>
                        <div class="text-center mt-3">
                            <form method="GET" class="form-inline justify-content-center container" style="margin-bottom: 10px;margin-top: 10px;" id="srchfrm">
                                     <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search By Name" id="search" style="margin-right: 20px;">
                                    <input type="text" name="contact" class="form-control" placeholder="Search By contact" id="contact" style="margin-right: 20px;">
                                    <div class="form-group" style="margin-right: 20px;">
                                        <label for="exampleInputEmail2" style="margin-right: 10px;">Branch</label>
                                        <select name="b_id" id="branch" class="form-control">
                                        <option value="" disabled selected>select</option>
                                          
                                          <?php
                                          $cat="select * from `branch`";
                                          $c_sql=mysqli_query($con,$cat);
                                           while ($c_data=mysqli_fetch_assoc($c_sql)) {
                                           ?>
                                          <option value="<?php echo $c_data['id']; ?>" <?php if(@$data['b_id']==@$c_data['id']){ echo "selected";} ?>>
                                              <?php echo $c_data['name']; ?>
                                          </option>
                                        <?php } ?>
                                        </select>
                                    </div>

                                        <div class="form-group" style="margin-right: 20px;">
                                        <label for="exampleInputEmail2" style="margin-right: 10px;">Inquiry BY</label>
                                        <select name="inq" id="inq" class="form-control">
                                        <option value="" disabled selected>select</option>
                                          <?php
                                            $userid = $_SESSION['userid'];
                                          if($_SESSION['roleid']==2){
                                             $cat="SELECT * FROM `admin`";
                                          }else{
                                             $cat="SELECT * FROM `admin` WHERE b_id = (SELECT b_id FROM `admin` WHERE id = $userid)";
                                          }
                                          $c_sql=mysqli_query($con,$cat);
                                          
                                           while ($userdata = mysqli_fetch_assoc($c_sql)) {
                                           ?>
                                          <option value="<?php echo $userdata['id']; ?>" <?php if(@$admindata['name']==@$userdata['name']){ echo "selected";} ?>>
                                              <?php echo $userdata['name']; ?>
                                          </option>
                                        <?php } ?>
                                        </select>
                                      </div>

                                     <div class="form-group">
                                            <label for="exampleInputEmail2" style="margin-right: 10px;">status</label>
                                            <select name="status" id="status" class="form-control">
                                            <option value="" disabled selected>select</option>
                                                  <?php
                                                  $cat = "select * from `status`";
                                                  $c_sql = mysqli_query($con, $cat);
                                                  while ($c_data = mysqli_fetch_assoc($c_sql)) {
                                                      ?>
                                                      <option value="<?php echo $c_data['id']; ?>" <?php if(@$data['status']==@$c_data['id']){ echo "selected";} ?>>
                                                          <?php echo $c_data['status']; ?>
                                                      </option>
                                                      <?php
                                                  }
                                                  ?>
                                            </select>
                                          </div>
                                         
                                </div>
                                <input type="hidden" name="page_no" value="1">
                            </form>
                            <form>
                                 <div class="form-group d-flex justify-content-center">
                                 <input type="submit" class="form-control btn bg-gradient-dark" style="width: auto;" value="EXCEL"> 
                             </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Branch</th>
                                        <th>Name<br>-----------<br>Contact</th>
                                        <th>Course<br>-----------<br>Detail</th>
                                        <th>Join Date</th>
                                        <th>Reference<br>-----------<br>Ref-Detail</th>
                                        <th>Inquiry By</th>
                                        <th>Status</th>
                                        <th>Add & view Follow Up</th>
                                        <?php if ($_SESSION['roleid'] == 2) { ?>
                                        <th>Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody id="ans">
                                    <?php 
                                    while($data=mysqli_fetch_assoc($res_p)){ ?>
                                    <tr class="<?php echo ($data['status'] == 'pending') ? 'blue' : (($data['status'] == 'declined') ? 'red' : 'green'); ?>">
                                        <td><?php echo $data['id']; ?></td>
                                        <td><?php echo $data['branch_name']; ?></td>
                                        <td><?php echo $data['name']; ?><br>---------<br><?php echo $data['contact']; ?></td>
                                        <td><?php 
                                            $select = explode(',', $data['course_id']);
                                            foreach ($select as $course) {
                                                $c_sql = "SELECT course FROM course WHERE id='$course'";
                                                $c_res = mysqli_query($con, $c_sql);
                                                $c_data = mysqli_fetch_assoc($c_res);
                                                echo $c_data['course'] . ",<br>";
                                            }
                                            echo "--------------<br>";
                                            echo $data['detail'];
                                        ?></td>
                                        <td><?php echo $data['joindate']; ?></td>
                                        <td><?php echo $data['reference']; ?><br>---------<br><?php echo $data['r_detail']; ?></td>
                                        <td><?php echo $data['a_name']; ?></td>
                                        <td><?php echo $data['status']; ?></td>
                                        <td><a href="add_follow.php?id=<?php echo $data['id']; ?>"><i class="fas fa-plus"></i></a><br><hr><br><a href="view_follow.php?id=<?php echo $data['id']; ?>"><i class="fas fa-eye"></i></a></td>
                                         <?php if ($_SESSION['roleid'] == 2) { ?>
                                        <td class="actions">
                                                 <a href="view_inquiry.php?id=<?php echo $data['id']; ?>" class="btn bg-gradient-danger" style="margin-bottom: 10px;">DELETE</a>
                                                 <a href="add_inquiry.php?id=<?php echo $data['id']; ?>" class="btn bg-gradient-primary">EDIT</a>
                                                             </td>
                                        <?php } ?>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right" id="page">
                            <?php
                            if ($total_pages > 1) {
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
                            }
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
<script>
    
       $(document).on('click','.page_no',function(){
        var page_no=$(this).attr('page-no');
         var search = $('#search').val();
         var contact = $('#contact').val();
         var branch_id = $('#branch').val();
         var inq = $('#inq').val();
         var status = $('#status').val();
        $.ajax({
            type:"get",
            url:"inquiry_ajax.php",
            data:{'page':page_no,'search': search,'contact': contact,'b_id':branch_id,'inq':inq,'status':status},
            success:function(res) {
                res=JSON.parse(res);
                $('#ans').html(res.tbl_data);
                $('#page').html(res.page_no);
            }
        })
       })

       $(document).on('change','#srchfrm',function(){

            var frm_data = $('#srchfrm').serialize();
            $.ajax({
                type:"get",
                url:"inquiry_ajax.php",
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
