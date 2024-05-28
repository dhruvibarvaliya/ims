<?php 
session_start();
include 'db.php';
 
  if(isset($_SESSION['userid'])){
    header("location:dashboard.php");
  }
   
  if(isset($_POST['submit']))
  {
    $email=$_POST['email'];
    $password=md5($_POST['password']);

    $sql="select * from `admin` where email='$email' and password='$password'";
    $res=mysqli_query($con,$sql);
    $cnt=mysqli_num_rows($res);
    // echo $cnt;
    if($cnt==1)
    {
      $data=mysqli_fetch_assoc($res);
      $_SESSION['userid']=$data['id'];
     $_SESSION['roleid']=$data['r_id']; 
     $_SESSION['b_id']=$data['b_id']; 

        date_default_timezone_set('Asia/Kolkata');
        $login_time = date('Y-m-d h:i:s A');
        $user_id = $data['id'];
        $login_query = "INSERT INTO `detail` (`id`,`login`) VALUES ('$user_id', '$login_time')";
         if (mysqli_query($con, $login_query)) {
            // Fetch the newly inserted d_id
            $d_id_query = "SELECT d_id FROM `detail` WHERE id='$user_id' ORDER BY d_id DESC LIMIT 1";
            $d_id_res = mysqli_query($con, $d_id_query);
            $d_id_row = mysqli_fetch_assoc($d_id_res);

            if ($d_id_row) {
                $_SESSION['d_id'] = $d_id_row['d_id'];
            } else {
                echo "Error: Unable to retrieve d_id.";
                exit();
            }

            header("location:dashboard.php");
            exit();
        }

    }else
    {
      $msg="email and password are wrong";
    }
  }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>IMS</b>Project</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block" name="submit">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

     

  
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
