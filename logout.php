<?php 
session_start();
include 'db.php';
date_default_timezone_set('Asia/Kolkata');

if (isset($_SESSION['userid']) && isset($_SESSION['d_id'])) {
    $user_id = $_SESSION['userid'];
    $d_id = $_SESSION['d_id']; 

    $logout_time = date('Y-m-d h:i:s A');
    $logout_query = "UPDATE detail SET logout = '$logout_time' WHERE d_id = '$d_id'";
    mysqli_query($con, $logout_query);
    session_destroy();
    header("location:index.php");
    exit();
} else {
    header("location:index.php");
    exit();
}
?>
