<?php

include 'db.php';
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:index.php");
}

$limit = 5;

$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : ''; 
$contact = isset($_GET['contact']) ? $_GET['contact'] : ''; 
$branch_id = isset($_GET['b_id']) ? $_GET['b_id'] : ''; 
$inq = isset($_GET['inq']) ? $_GET['inq'] : ''; 
$status = isset($_GET['status']) ? $_GET['status'] : ''; 
$roleid = $_SESSION['roleid'];
$userid = $_SESSION['userid'];
$sql_p = "SELECT inquiry.*, branch.name as branch_name, course.course, reference.reference,admin.name as        a_name, status.status from inquiry 
          inner join branch on inquiry.branch=branch.id 
          inner join course on inquiry.course_id=course.id 
          inner join reference on inquiry.reference=reference.id 
          inner join admin on inquiry.inquiryby=admin.id 
          inner join status on inquiry.status=status.id WHERE inquiry.name LIKE '%$search%' and inquiry.contact LIKE '%$contact%' and branch.id like '%$branch_id%' and admin.id like '%$inq%' and status.id like '%$status%'";
if ($roleid != 2) {
    $sql_p = "SELECT inquiry.*, branch.name as branch_name, course.course, reference.reference,admin.name as        a_name, status.status from inquiry 
          inner join branch on inquiry.branch=branch.id 
          inner join course on inquiry.course_id=course.id 
          inner join reference on inquiry.reference=reference.id 
          inner join admin on inquiry.inquiryby=admin.id 
          inner join status on inquiry.status=status.id WHERE inquiry.name LIKE '%$search%' and inquiry.contact LIKE '%$contact%' and branch.id like '%$branch_id%' and admin.id like '%$inq%' and status.id like '%$status%' and inquiry.branch = (SELECT b_id FROM admin WHERE id = $userid)";
}
$sql_p .= " ORDER BY inquiry.id DESC";

$res_p = mysqli_query($con, $sql_p);

$total_records = mysqli_num_rows($res_p);

$total_pages = ceil($total_records / $limit);
$sql_p .= " LIMIT $limit OFFSET $offset";
$res_p = mysqli_query($con, $sql_p);


$tbl_data = "";
while ($data = mysqli_fetch_assoc($res_p)) {
   $tbl_data .= "<tr class=\"" . (($data['status'] == 'pending') ? 'blue' : (($data['status'] == 'declined') ? 'red' : 'green')) . "\">";
    $tbl_data .= "<td>" . $data['id'] . "</td>";
    $tbl_data .= "<td>" . $data['branch_name'] . "</td>";
    $tbl_data .= "<td>" . $data['name'] ."<br>---------<br>".$data['contact']."</td>";
    $courses = explode(',', $data['course_id']);
    $course_names = array();
    foreach ($courses as $course) {
        $c_sql = "SELECT course FROM course WHERE id='$course'";
        $c_res = mysqli_query($con, $c_sql);
        $c_data = mysqli_fetch_assoc($c_res);
        $course_names[] = $c_data['course'];
    }
    $tbl_data .= "<td>" . implode(', ', $course_names) . "<br>---------<br>".$data['detail']."</td>";
    $tbl_data .= "<td>" . $data['joindate'] . "</td>";

    $tbl_data .= "<td>" . $data['reference'] . "<br>---------<br>".$data['r_detail']."</td>";
    $tbl_data .= "<td>" . $data['a_name'] . "</td>";
    $tbl_data .= "<td>" . $data['status'] . "</td>";
    $tbl_data .= "<td><a href='add_follow.php?id=" . $data['id'] . "'><i class='fas fa-plus'></i></a>"."<br><hr><br>"."<a href='view_follow.php?id=" . $data['id'] . "'><i class='fas fa-eye'></i></a></td>";
    $tbl_data .= "<td class='actions'><a href='view_inquiry.php?id=" . $data['id'] . "' class='btn bg-gradient-danger' style='margin-bottom: 10px;'>DELETE</a>";
    $tbl_data .= "<a href='add_inquiry.php?id=" . $data['id'] . "' class='btn bg-gradient-primary'>EDIT</a></td>";

    $tbl_data .= "</tr>";
}


$pagination_links = "";
if ($total_pages > 1) {
    if ($page > 1) {
        $pagination_links .= '<li class="page-item"><a class="page-link page_no" href="javascript:void(0)" page-no="' . ($page - 1) . '">Previous</a></li>';
    } else {
        $pagination_links .= '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $page) {
            $pagination_links .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            $pagination_links .= '<li class="page-item"><a class="page-link page_no" href="javascript:void(0)" page-no="' . $i . '">' . $i . '</a></li>';
        }
    }

    if ($page < $total_pages) {
        $pagination_links .= '<li class="page-item"><a class="page-link page_no" href="javascript:void(0)" page-no="' . ($page + 1) . '">Next</a></li>';
    } else {
        $pagination_links .= '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }
}

$response_array = array('tbl_data' => $tbl_data, 'page_no' => $pagination_links);

echo json_encode($response_array); 

?>
