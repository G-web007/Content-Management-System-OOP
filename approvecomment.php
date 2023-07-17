<?php 
require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

if(isset($_GET['id'])){
    $searchid = $_GET['id'];
    global $connectionDB;
    $admin = $_SESSION['adminusername'];
    $sql = "UPDATE `comments` SET `status` = 'ON', `approve` = '$admin' WHERE `id` = '$searchid'";
    $excute = $connectionDB->query($sql);
    if($excute){
        $_SESSION['SuccessMessage'] = "Comment Approved Successfully!";
        Redirect_To('comments.php');
    } else {
        $_SESSION['ErrorMessage'] = "Something Went Wrong. Try Again!";
        Redirect_To('comments.php');
    }
}

?>