<?php 
require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

if(isset($_GET['id'])){
    $searchid = $_GET['id'];
    global $connectionDB;
    $sql = "DELETE FROM `comments` WHERE `id` = '$searchid'";
    $excute = $connectionDB->query($sql);
    if($excute){
        $_SESSION['SuccessMessage'] = "Comment Deleted!";
        Redirect_To('comments.php');
    } else {
        $_SESSION['ErrorMessage'] = "Something Went Wrong. Try Again!";
        Redirect_To('comments.php');
    }
}

?>