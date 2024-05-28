<?php 

include 'db.php';
session_start();
if(!isset($_SESSION['userid']))
   {
    header("location:index.php");
   }

   $data = array(); 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sel = "SELECT * FROM `inquiry` WHERE id=$id"; 
    $res = mysqli_query($con, $sel);
    $data = mysqli_fetch_assoc($res);
}

if (isset($_POST['submit'])) {
    $branch = $_POST['branch'];
    $name = $_POST['name'];
    $contact =$_POST['contact'];
    if(isset($_POST['course_id'])) {
        $courses = $_POST['course_id'];
        $select = implode(",", $courses); 
    } else {
        $select = "";
    }
    $detail = $_POST['detail'];
    $joindate = $_POST['joindate'];
    $reference = $_POST['reference'];
    $r_detail = $_POST['r_detail'];
    $inquiryby = $_POST['inquiryby'];
    $status = $_POST['status'];

    $contact1="select * from `inquiry` where contact='$contact'";
    $con_result=mysqli_query($con,$contact1);
    $number = mysqli_num_rows($con_result);
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
         if($number==0)
        {
        $sql = "UPDATE `inquiry` SET branch='$branch',name='$name', contact='$contact', course_id='$select', detail='$detail',joindate='$joindate',reference='$reference',r_detail='$r_detail',inquiryby='$inquiryby',status='$status' WHERE id=$id"; 
         mysqli_query($con, $sql);
        header("location:view_inquiry.php");
         }else{
           $msg="contact already exist";
        }
    } else {
         if($number==0)
        {
             $sql = "INSERT INTO `inquiry` (`branch`,`name`,`contact`,`course_id`,`detail`,`joindate`,`reference`,`r_detail`,`inquiryby`,`status`) VALUES ('$branch','$name', '$contact','$select','$detail','$joindate','$reference','$r_detail','$inquiryby','$status')";
             $res = mysqli_query($con, $sql);
              $last_id =  mysqli_insert_id($con); 

            $followdetail = "insert into `followup`(`inq_id`,`f_reason`,`joindate`,`f_by`)values('$last_id','$detail','$joindate','$inquiryby')";
            mysqli_query($con,$followdetail);
            header("location:view_inquiry.php");
               }else{
           $msg="contact already exist";
        }

            
            
    }
    
           

    
}
$userid = $_SESSION['userid'];
$cat="select * from `admin` WHERE id = $userid";
 $c_sql=mysqli_query($con,$cat);
 $admindata = mysqli_fetch_assoc($c_sql);

                   

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
            <h1>Add Inquiry</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Inquiry</li>
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
                <h3 class="card-title">Add Inquiry</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
               <br>
                <h3 class="card-title" style="margin-left: 10px;color: red;"><?php echo @$msg; ?></h3>
              <form method="post" enctype="multipart/form-data" id="frm">
                <div class="card-body">
                    <div class="form-group">
                    <label for="exampleInputEmail2">Branch</label>
                    <select name="branch" id="branch" class="form-control">
                    <option value="" selected>select</option>
                      <?php
                      $cat="select * from `branch`";
                      $c_sql=mysqli_query($con,$cat);
                       while ($c_data=mysqli_fetch_assoc($c_sql)) {
                       ?>
                      <option value="<?php echo $c_data['id']; ?>" <?php if(@$admindata['b_id']==@$c_data['id']){ echo "selected";} ?>>
                          <?php echo $c_data['name']; ?>
                      </option>
                    <?php } ?>
                    </select>

                    <span style="color:red;display: none;">select branch...</span>
                    
                    
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="<?php echo @$data['name'] ?>">
                    <span style="color:red;display: none;">enter name...</span>

                  </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Contact</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+91</span>
                        </div>
                        <input type="tel"  id="contact" class="form-control" name="contact"  placeholder="Enter contact (10 digits)" value="<?php echo @$data['contact'] ?>">
                    </div>
                        <span  id="con_reg" style="color:red;display: none;">Enter a valid Indian contact number (+91 followed by 10 digits)</span>
                </div>

                   <div class="form-group" id="c_id">
                    <label for="exampleInputPassword1">Course</label><br>
                    <select name="course_id[]" multiple="multiple" id="course_id" data-placeholder="Select course" class="select2 col-12">

                      <?php
                      $select = "SELECT * FROM course";
                      $result = mysqli_query($con, $select);
                      while ($r_data = mysqli_fetch_assoc($result)) {
                         
                          $selected = (isset($data['course_id']) && in_array($r_data['id'], explode(',', $data['course_id']))) ? 'selected' : '';
                          ?>
                          <option value="<?php echo $r_data['id']; ?>" <?php echo $selected; ?>>
                              <?php echo $r_data['course']; ?>
                          </option>
                      <?php } ?>
                  </select>
                  </div>
                  <span style="color:red;display: none;">Select atleast one course...</span>

                 <div class="form-group">
                    <label for="exampleInputEmail1">Extra Details</label>
                    <input type="text" class="form-control" name="detail" id="detail" placeholder="Enter detail" value="<?php echo @$data['detail'] ?>">
                    <span style="color:red;display: none;">enter detail...</span>

                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Join Date</label>
                    <input type="date" class="form-control" name="joindate" id="joindate" placeholder="Enter date" value="<?php echo @$data['joindate'] ?>" min="<?php echo date('Y-m-d'); ?>">
                    <span style="color:red;display: none;">enter joindate...</span>

                  </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail2">Reference</label>
                                <select name="reference" id="reference" class="form-control">
                                    <option value="" selected>select</option>
                                    <?php
                                    $cat="select * from `reference`";
                                    $c_sql=mysqli_query($con,$cat);
                                    while ($c_data=mysqli_fetch_assoc($c_sql)) {
                                    ?>
                                        <option value="<?php echo $c_data['id']; ?>" <?php if(@$data['reference']==@$c_data['id']){ echo "selected";} ?>>
                                            <?php echo $c_data['reference']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <!-- Error message for reference -->
                                <span style="color:red;display: none;">select reference...</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Details</label>
                                <input type="text" class="form-control" name="r_detail" id="r_detail" placeholder="Enter reference details" value="<?php echo @$data['r_detail'] ?>">
                                <!-- Error message for reference details -->
                                <span style="color:red;display: none;">enter reference details...</span>
                            </div>
                        </div>
                    </div>

                  <div class="form-group d-flex">
                    <label for="exampleInputEmail2">Inquiry BY</label>
                    <select name="inquiryby" id="inquiryby" class="form-control">
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
                    <span style="color:red;display: none;">select inquiry...</span>
                  </div>
                  
                   <div class="form-group">
                    <label for="exampleInputEmail2">status</label>
                    <select name="status" id="status" class="form-control">
                          <?php
                          $cat = "select * from `status`";
                          $c_sql = mysqli_query($con, $cat);
                          while ($c_data = mysqli_fetch_assoc($c_sql)) {
                              ?>
                              <option value="<?php echo $c_data['id']; ?>" <?php if(@$data['status']==@$c_data['id'] || @$data['status'] === 'pending'){ echo "selected";} ?>>
                                  <?php echo $c_data['status']; ?>
                              </option>
                              <?php
                          }
                          ?>
                    </select>
                    <span style="color:red;display: none;">select status...</span>
                    
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
 <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
 <!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<script type="text/javascript">
    $('#frm').submit(function () {
        var name = $('#name').val();
        var contact = $('#contact').val();
        var course_id = $('#course_id').val();
        var detail = $('#detail').val();
        var joindate = $('#joindate').val();
        var reference = $('#reference').val();
        var r_detail = $('#r_detail').val();
        var s_date = $('#s_date').val();

        var contactRegex = /^[0-9]{10}$/;

        if (name.trim() === '') {
            $('#name').next('span').css('display', 'inline');
            return false;
        } else {
            $('#name').next('span').css('display', 'none');
        }
        if (contact.trim() === '' || !contactRegex.test(contact)) {
            $('#con_reg').css('display', 'inline').text('Enter a valid Indian contact number (+91 followed by 10 digits)');
            return false;
        } else {
            $('#con_reg').css('display', 'none');
        }
         if (course_id == '') {
            $('#c_id').next('span').css('display', 'inline');
            return false;
        } else {
            $('#c_id').next('span').css('display', 'none');
        }
        if (detail.trim() === '') {
            $('#detail').next('span').css('display', 'inline');
            return false;
        } else {
            $('#detail').next('span').css('display', 'none');
        }
        if (joindate.trim() === '') {
            $('#joindate').next('span').css('display', 'inline');
            return false;
        } else {
            $('#joindate').next('span').css('display', 'none');
        }
        if (reference === '') {
            $('#reference').next('span').css('display', 'inline');
            return false;
        } else {
            $('#reference').next('span').css('display', 'none');
        }
        if (r_detail.trim() === '') {
            $('#r_detail').next('span').css('display', 'inline');
            return false;
        } else {
            $('#r_detail').next('span').css('display', 'none');
        }
        if (s_date.trim() === '') {
            $('#s_date').next('span').css('display', 'inline');
            return false;
        } else {
            $('#s_date').next('span').css('display', 'none');
        }
    });
</script>
</body>
</html>
