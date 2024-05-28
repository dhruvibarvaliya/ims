<?php 
include'db.php';
// session_start();
$id=$_SESSION['userid'];

$sql = "SELECT admin.*, role.position, branch.name as b_name FROM admin 
        INNER JOIN role ON admin.r_id = role.id 
        INNER JOIN branch ON admin.b_id = branch.id 
        WHERE admin.id = " . $id;
$res=mysqli_query($con,$sql);
$data1=mysqli_fetch_assoc($res);
// print_r($data1);die();
$_SESSION['roleid']=$data1['r_id']; 
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>
  <script src="dist/js/autologout.js"></script>
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
       <li class="nav-item">
        <a class="nav-link btn" data-widget="button" href="change_pw.php" role="button"><i class="fas fa-key"> Change Password</i></a>
      </li>
        <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>
                Logout
            </a>
          </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center ">
        <div class="image">
          <img src="image/admin/<?php echo $data1['a_image'] ?>" class="rounded-circle" alt="User Image">
        </div>
        <div class="info">
         <a href="#" class="d-block"><?php echo $data1['name'] . ' / ' . $data1['position']; ?></a>
        <a href="#" class="d-block"><?php echo ' Branch:- ' . $data1['b_name']; ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="dashboard.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
       
          <?php if($_SESSION['roleid']==2){ ?>

            
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Admin
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_admin.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Addmin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="view_admin.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Admin</p>
                </a>
              </li>
            </ul>
          </li>
            
            <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-image"></i>
              <p>
                Role
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_Role.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Role</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="view_Role.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Role</p>
                </a>
              </li>
            </ul>
          </li>


           <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tag"></i>
              <p>
                Branch
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_branch.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Branch</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="view_branch.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Branch</p>
                </a>
              </li>
            </ul>
          </li>


       


          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
               Course
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_course.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Course</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="view_course.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Course</p>
                </a>
              </li>
            </ul>
          </li>




           <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-blog"></i>
              <p>
                Reference
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_reference.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Reference</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="view_reference.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Reference</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-address-card"></i>
              <p>
                Status
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_status.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Status</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="view_status.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Status</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Inquiry
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_inquiry.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Inquiry</p>
                </a>
              </li>
            
              <li class="nav-item">
                <a href="view_inquiry.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Inquiry</p>
                </a>
              </li>
            </ul>
          </li>
        <?php } elseif ($_SESSION['roleid']==5) {?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Inquiry
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="view_inquiry.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Inquiry</p>
                </a>
              </li>
            </ul>
          </li>
     <?php   }?>
         <?php if ($_SESSION['roleid'] != 2 && $_SESSION['roleid'] != 5) { ?>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Inquiry
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_inquiry.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Inquiry</p>
                </a>
              </li>
            
              <li class="nav-item">
                <a href="view_inquiry.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View Inquiry</p>
                </a>
              </li>
            </ul>
          </li>
     <?php   }?>


          <li class="nav-item">
            <a href="detail.php" class="nav-link">
              <i class="nav-icon fas fa-angle-left"></i>
              <p>
                Login Report
              </p>
            </a>
          </li>

           <li class="nav-item">
            <a href="pw_detail.php" class="nav-link">
              <i class="nav-icon fas fa-lock"></i>
              <p>
                Password Report
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="branch_report.php" class="nav-link">
              <i class="nav-icon fas fa-code-branch"></i>
              <p>
                Branch Report
              </p>
            </a>
          </li>


          <li class="nav-item">
            <a href="faculty_report.php" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Faculty Report
              </p>
            </a>
          </li>

          




          




      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
