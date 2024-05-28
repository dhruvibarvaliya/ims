<?php 

include 'db.php';
session_start();
if(!isset($_SESSION['userid']))
   {
    header("location:index.php");
   }

if (isset($_POST['submit'])) {
   
    $position = $_POST['position'];

    
    if (isset($_GET['id'])) {
      
        $id = $_GET['id'];
         $sel="select * from `role` where position='$position' AND id != $id";
         $res1=mysqli_query($con,$sel);
         if(mysqli_num_rows($res1)==0)
        {
        $sql = "UPDATE `role` SET position='$position' WHERE id=$id"; 
         mysqli_query($con, $sql);
          header("location:view_role.php");
           }else{
          $msg="role already exist";
        }
    } else {
       $sel="select * from `role` where position='$position'";
      $res1=mysqli_query($con,$sel);
      if(mysqli_num_rows($res1)==0)
        {
        $sql = "INSERT INTO `role` (`position`) VALUES ('$position')";
          mysqli_query($con, $sql);
          header("location:view_role.php");
     }else{
          $msg="role already exist";
        }
           
    }

  
}

$data = array(); 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sel = "SELECT * FROM `role` WHERE id=$id";
    $res = mysqli_query($con, $sel);
    $data = mysqli_fetch_assoc($res);
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
            <h1>Add Role</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Role</li>
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
                <h3 class="card-title">Add Role</h3>

              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <br>
                <h3 class="card-title" style="margin-left: 10px;color: red;"><?php echo @$msg; ?></h3>
              <form method="post" enctype="multipart/form-data" id="frm">
                <div class="card-body">

                  <div class="form-group">
                    <label for="exampleInputEmail1">Position</label>
                    <input type="text" class="form-control" name="position" id="position" placeholder="Enter position" value="<?php echo @$data['position'] ?>">
                    <span style="color:red;display: none;">enter position...</span>

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
  $('#frm').submit(function(){
    var position=$('#position').val();
     if(position=='')
    {
      $('#position').next('span').css('display','inline');
      return false;
    }else{
      $('#position').next('span').css('display','none');
    }

  })
  
</script>
</body>
</html>
