<?php 
require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

if(isset($_GET['id'])){
    $adminid = $_GET['id'];

    $sql = "DELETE FROM `admin` WHERE `id` = '$adminid'";
    $excute = $connectionDB->query($sql);
    if($excute){
        $_SESSION['SuccessMessage'] = "Admin Deleted!";
        Redirect_To('admin.php');
    } else {
        $_SESSION['ErrorMessage'] = "Something Went Wrong. Try Again!";
        Redirect_To('admin.php');
    }
}
?>