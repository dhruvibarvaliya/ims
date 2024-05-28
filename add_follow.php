<?php 

include 'db.php';
session_start();
if(!isset($_SESSION['userid'])) {
    header("location:index.php");
}

$data = array(); 
if (isset($_GET['f_id'])) {
    $id = $_GET['f_id'];
    $sel = "SELECT followup.*, inquiry.name, inquiry.contact, inquiry.course_id,admin.name AS a_name,course.course FROM `followup` INNER JOIN `inquiry` ON followup.inq_id = inquiry.id INNER JOIN `admin` ON inquiry.inquiryby = admin.id  INNER JOIN `course` ON inquiry.course_id = course.id WHERE followup.f_id = $id";
    $res = mysqli_query($con, $sel);
    $data = mysqli_fetch_assoc($res);
}

if (isset($_POST['submit'])) {
    if(isset($_GET['id'])) {
        $inq_id=$_GET['id'];
    }
    $f_reason = $_POST['f_reason'];
    $joindate = $_POST['joindate'];
    $f_by = $_POST['f_by'];

    $sql = "INSERT INTO `followup` (`inq_id`,`f_reason`,`joindate`,`f_by`) VALUES ('$inq_id','$f_reason','$joindate','$f_by')"; 
    mysqli_query($con, $sql);
    header("location:view_follow.php?id=$inq_id");
    exit();
}

$userid = $_SESSION['userid'];
$cat="select * from `admin` WHERE id = $userid";
$c_sql=mysqli_query($con,$cat);
$admindata = mysqli_fetch_assoc($c_sql);
           
if(isset($_GET['id'])) {
    $id=$_GET['id'];
    $inquiry="SELECT * from `inquiry` where id=$id"; 
    $result=mysqli_query($con,$inquiry);
    $inquirydata=mysqli_fetch_assoc($result); 
}
?>    
<?php 
  include ("header.php");
 ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add followup</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <div class="text-center" style="margin-right:30px;">
                    <a href="view_follow.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary">View Followup</a>
                </div>
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add followup</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add followup</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" enctype="multipart/form-data" id="frm">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Inquiry Name</label>
                    <input type="text" class="form-control" name="i_name" id="i_name" placeholder="Enter Inquiry Name" value="<?php echo @$inquirydata['name'] ?>" disabled>
                  </div>
                   <div class="form-group">
                    <label for="exampleInputEmail1">contact</label>
                    <input type="text" class="form-control" name="contact" id="contact" placeholder="" value="<?php echo @$inquirydata['contact'] ?>" disabled>
                  </div>

                
                   <div class="form-group">
                      <?php 
                      $select1 = "SELECT inquiry.*, GROUP_CONCAT(course.course SEPARATOR ', ') AS c_courses 
                                  FROM inquiry 
                                  INNER JOIN course ON FIND_IN_SET(course.id, inquiry.course_id) > 0 
                                  WHERE inquiry.id = " . $_GET['id'] . "
                                  GROUP BY inquiry.id";

                      $result1 = mysqli_query($con, $select1);
                      $data1 = mysqli_fetch_assoc($result1);
                      ?>
                      <label for="exampleInputEmail1" style="margin-left:10px;">Courses</label>
                      <input type="text" class="form-control" name="courses" value="<?php echo @$data1['c_courses'] ?>" disabled>
                  </div>
 
                  
                   <div class="form-group">
                    <label for="exampleInputEmail1">Inquiry By</label>
                    <?php 
                      $inquiry="SELECT inquiry.*,admin.name as a_name from inquiry inner join admin on inquiry.inquiryby=admin.id where inquiry.id=".$_GET['id'];
                      $result=mysqli_query($con,$inquiry);
                      $i_data=mysqli_fetch_assoc($result);
                     ?>
                    <input type="text" class="form-control" name="a_name" id="a_name" placeholder="Enter Inquiry Name" value="<?php echo @$i_data['a_name'] ?>" disabled>
                  </div>
                    
                      <div class="form-group">
                    <label for="exampleInputEmail1">Follow-up Reason</label>
                    <input type="text" class="form-control" name="f_reason" id="f_reason" placeholder="enter reason..." value="<?php echo @$data['f_reason'] ?>">
                    <span style="color:red;display: none;">select Reason...</span>

                  </div>

                    <div class="form-group">
                    <label for="exampleInputEmail1">Expected Join Date</label>
                    <input type="date" class="form-control" name="joindate" id="joindate" placeholder="" value="<?php echo @$data['joindate'] ?>" min="<?php echo date('Y-m-d'); ?>">
                    <span style="color:red;display: none;">select Joi Date...</span>

                  </div>  

                  <div class="form-group d-flex">
                    <label for="exampleInputEmail2">Follow-up BY</label>
                    <select name="f_by" id="f_by" class="form-control">
                    <option value="" selected>select</option>
                      <?php
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
                    <span style="color:red;display: none;">select follow-up by...</span>
                  </div>

                  

                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
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
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page specific script -->
<script type="text/javascript">
    $('#frm').submit(function () {
        var f_reason = $('#f_reason').val();
        var joindate = $('#joindate').val();

        if (f_reason.trim() === '') {
            $('#f_reason').next('span').css('display', 'inline');
            return false;
        } else {
            $('#f_reason').next('span').css('display', 'none');
        }
        if (joindate.trim() === '') {
            $('#joindate').next('span').css('display', 'inline');
            return false;
        } else {
            $('#joindate').next('span').css('display', 'none');
        }
       
    });
</script>
</body>
</html>
