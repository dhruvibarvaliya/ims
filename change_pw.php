<?php 
include 'db.php';
   session_start();
  if (!isset($_SESSION['userid'])) {
      header("location:index.php");
    }

 $userid=$_SESSION['userid']; 
    if(isset($_POST['submit'])){

    	$password=md5($_POST['password']);
        $sql="select * from admin where `id`=$userid and `password`='$password'";
    	$res=mysqli_query($con,$sql);
  		  $cnt=mysqli_num_rows($res);
    	if($cnt==1){
    		$_SESSION['change_pw']=1;
    		header("location:update_pw.php");
    	}else{
    		$msg="your current password is wrong";
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
                    <label for="exampleInputEmail1">Cureent Password</label>
                    <input type="password" class="form-control" name="password" id="c_pw" placeholder="Enter Current Password">
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
    var c_pw = $('#c_pw').val();
  

    
    var isInsert = <?php echo isset($_GET['id']) ? 'false' : 'true'; ?>;

    if(isInsert) { 
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
