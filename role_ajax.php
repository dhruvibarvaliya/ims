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

$sql_p = "SELECT * FROM role WHERE position like '%$search%'";

$res_p = mysqli_query($con, $sql_p);

$total_records = mysqli_num_rows($res_p);

$total_pages = ceil($total_records / $limit);
$sql_p .= " LIMIT $limit OFFSET $offset";
$res_p = mysqli_query($con, $sql_p);


$tbl_data = "";
while ($data = mysqli_fetch_assoc($res_p)) {
   $tbl_data .= "<tr>";
    $tbl_data .= "<td>" . $data['id'] . "</td>";
    $tbl_data .= "<td>" . $data['position'] . "</td>";
    $tbl_data .= "<td class='actions'><a href='view_role.php?id=" . $data['id'] . "' class='btn bg-gradient-danger' style='margin-right: 3px;'>DELETE</a>";
    $tbl_data .= "<a href='view_role.php?id=" . $data['id'] . "' class='btn bg-gradient-primary'>EDIT</a></td>";

    $tbl_data .= "</tr>";
}


$pagination_links = "";

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


$response_array = array('tbl_data' => $tbl_data, 'page_no' => $pagination_links);

echo json_encode($response_array); 

?>
