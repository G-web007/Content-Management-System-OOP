<?php 
require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

if(isset($_GET['id'])){
    $categoryid = $_GET['id'];

    $sql = "DELETE FROM `category` WHERE `id` = '$categoryid'";
    $excute = $connectionDB->query($sql);
    if($excute){
        $_SESSION['SuccessMessage'] = "Category Deleted!";
        Redirect_To('categories.php');
    } else {
        $_SESSION['ErrorMessage'] = "Something Went Wrong. Try Again!";
        Redirect_To('categories.php');
    }
}
?>