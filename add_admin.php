<?php 
include 'db.php';
session_start();
if(!isset($_SESSION['userid'])) {
    header("location:index.php");
}

$isEdit = isset($_GET['id']);
$data = array(); 

if ($isEdit) {
    $id = $_GET['id'];
    $sel = "SELECT * FROM `admin` WHERE id=$id";
    $res = mysqli_query($con, $sel);
    $data = mysqli_fetch_assoc($res);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $image=$_FILES['a_image']['name'];
    if($image=="") {
        $image=$data['a_image'];
    } else {
        $image = rand(10000,99999).'admin'.$image;
        unlink('image/admin/'.$data['a_image']);
        $path = "image/admin/" . $image;
        move_uploaded_file($_FILES['a_image']['tmp_name'], $path);
    }
    $r_id = $_POST['r_id'];
    $b_id = $_POST['b_id'];

    if ($isEdit) {
        $sql1= "SELECT * FROM `admin` WHERE email='$email' AND id != $id";
        $res1=mysqli_query($con,$sql1);
        
        if (mysqli_num_rows($res1) == 0) {
            if ($_SESSION['userid'] == $id  && $email !== $data['email']) {
               $sql = "UPDATE admin SET email='$email' WHERE id=$id";
                mysqli_query($con, $sql);
                $_SESSION['userid'] = $id; 
                header("location:logout.php");
            } else {
              $sql = "UPDATE `admin` SET name='$name', email='$email',a_image='$image', r_id='$r_id', b_id='$b_id' WHERE id=$id";
            mysqli_query($con, $sql);
                header("location:view_admin.php"); 
            }
        } else {
            $msg = 'Email already exists!';
        }
    } else {
        $sql1 = "SELECT * FROM `admin` WHERE email='$email'";
        $res1 = mysqli_query($con, $sql1);
        
        if (mysqli_num_rows($res1) == 0) {
            $sql = "INSERT INTO `admin` (`name`, `email`, `password`, `a_image`, `r_id`, `b_id`) VALUES ('$name', '$email', '$password', '$image', '$r_id', '$b_id')";
            mysqli_query($con, $sql);
            header("location:view_admin.php");
        } else {
            $msg = "Email already exists!";
        }
    }
}
?>

<?php 
  include ("header.php");
 ?>
<style type="text/css">
  #password_field {
        display: <?php echo $isEdit ? 'none' : 'block'; ?>;
    }
 </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Admin</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Admin</li>
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
                <h3 class="card-title">Add Admin</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
               <br>
                <h3 class="card-title" style="margin-left: 10px;color: red;"><?php echo @$msg; ?></h3>
              <form method="post" enctype="multipart/form-data" id="frm">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="<?php echo @$data['name'] ?>">
                    <span style="color:red;display: none;">enter name...</span>

                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" value="<?php echo @$data['email'] ?>">
                    <span style="color:red;display: none;">enter email...</span>

                  </div>
                  <div class="form-group" id="password_field">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo @$data['password'] ?>">
                    <span style="color:red;display: none;">The password should be at least 6 characters long with letter,special symbol...</span>

                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group"> 
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="a_image" attr_data="<?php echo @$data['a_image']; ?>">
                         <span style="color:red;display: none;margin-top: 50px;width: 650px;height: 10px;">select image [ only jpg file is allowed ]...</span>
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                        <?php if(isset($data['a_image'])): ?>
                            <div style="height:70px; width: 70px; overflow:hidden;">
                                <img src="image/admin/<?php echo $data['a_image']; ?>" alt="Profile Picture" style="height: 100%; width:100%; object-fit:cover;">
                            </div>
                        <?php endif; ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail2">Branch</label>
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
                    <span style="color:red;display: none;">select branch...</span>
                    
                  </div>
                      <div class="form-group">
                    <label for="exampleInputEmail2">Role</label>
                    <select name="r_id" id="role" class="form-control">
                    <option value="" selected disabled>select</option>
                      
                      <?php
                      $cat="select * from `role`";
                      $c_sql=mysqli_query($con,$cat);
                       while ($c_data=mysqli_fetch_assoc($c_sql)) {
                       ?>
                      <option value="<?php echo $c_data['id']; ?>" <?php if(@$data['r_id']==@$c_data['id']){ echo "selected";} ?>>
                          <?php echo $c_data['position']; ?>
                      </option>
                    <?php } ?>
                    </select>
                    <span style="color:red;display: none;">select role...</span>
                    
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
    var name=$('#name').val();
    var email=$('#email').val();
    var e_pt=/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/;
    var password=$('#password').val();
    var p_pt=/^(?=.*[a-z])(?=.*\d)(?=.*[@$!%*#?&])[a-z\d@$!%*#?&]{6,}$/;
    var branch=$('#branch').val();
    var role=$('#role').val();

      var isInsert = <?php echo isset($_GET['id']) ? 'false' : 'true'; ?>;

    if(isInsert) { 
    if(name=='')
    {
      $('#name').next('span').css('display','inline');
      return false;
    }else{
      $('#name').next('span').css('display','none');
    }
    if(e_pt.test(email)==false)
    {
      $('#email').next('span').css('display','inline');
      return false;
    }else{
      $('#email').next('span').css('display','none');
    }
    if(p_pt.test(password)==false)
    {
      $('#password').next('span').css('display','inline');
      return false;
    }else{
      $('#password').next('span').css('display','none');
    }
   if(image === '') {
            $('#image').next('span').css('display', 'inline');
            return false;
        } else {
            $('#image').next('span').css('display', 'none'); 
        }

        var validImageTypes = ['image/jpeg'];
        var fileType = $('#image')[0].files[0].type;
        if ($.inArray(fileType, validImageTypes) === -1) {
            $('#image').next('span').css('display', 'inline');
            return false;
        } else {
            $('#image').next('span').css('display', 'none'); 
        }
       if(branch=='')
    {
      $('#branch').next('span').css('display','inline');
      return false;
    }else{
      $('#branch').next('span').css('display','none');
    }
     if(role=='')
    {
      $('#role').next('span').css('display','inline');
      return false;
    }else{
      $('#role').next('span').css('display','none');
    }
       }   

  })
  
</script>

</body>
</html>
