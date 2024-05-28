<?php 
include 'db.php';
   session_start();
  if (!isset($_SESSION['userid'])) {
      header("location:index.php");
    }
    if(!isset($_SESSION['change_pw'])){
    	header("location:change_pw.php");
    }

    if(isset($_POST['submit'])){
    	$newpw=$_POST['newpw'];
    	$conpw=$_POST['conpw'];
    	$userid=$_SESSION['userid'];
    	if($newpw == $conpw){	
    		$updatepw=md5($newpw);
    		$sql="update admin set `password`='$updatepw' where `id`=".$userid;
    		  if (mysqli_query($con, $sql)) {
          date_default_timezone_set('Asia/Kolkata');
          $current_time = date('Y-m-d h:i:s A');
            $pw_sql = "INSERT INTO `pw_detail` (`user_id`, `update_time`) VALUES ('$userid', '$current_time')";
            mysqli_query($con, $pw_sql);
            
            $msg = "Password updated...";
            header("location:logout.php");
        } else {
            $msg = "Failed to update password";
        }
    	}else{
    		$msg="new and confirm password not match";
    	}
        
    }
 ?>
 <?php 
  include ("header.php");
 ?>
 
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Change Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Change Password</li>
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
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Change Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <h1 style="color:red;font-size: 20px;margin-left: 10px;"><?php echo @$msg; ?></h1>
              <form method="post" enctype="multipart/form-data" id="frm">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">New Password</label>
                    <input type="password" class="form-control" name="newpw" id="n_pw" placeholder="Enter New Password">
                    <span style="color:red;display: none;">enter atleast 8 characters(with digit and characters)...</span>
                  </div> 
                  <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" class="form-control" name="conpw" id="c_pw" placeholder="Enter Confirm Password">
                    <span style="color:red;display: none;">enter atleast 8 characters(with digit and characters)...</span>
                  </div>
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
$('#frm').submit(function(){
    var p_pt=/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%*#?&])[a-z\d@$!%*#?&]{6,}$/;
  
    var n_pw = $('#n_pw').val();
    var c_pw = $('#c_pw').val();
  

    
    var isInsert = <?php echo isset($_GET['id']) ? 'false' : 'true'; ?>;

    if(isInsert) { 
         if(p_pt.test(c_pw)==false)
        {
          $('#n_pw').next('span').css('display','inline');
          return false;
        }else{
          $('#n_pw').next('span').css('display','none');
        }
       if(p_pt.test(c_pw)==false)
        {
          $('#c_pw').next('span').css('display','inline');
          return false;
        }else{
          $('#c_pw').next('span').css('display','none');
        }

        
    }

    return true;
});
</script>

</body>
</html>
