<?php

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';
confirm_login();

if(!empty($_GET['id'])){
    global $connectionDB;
    $sql = "DELETE FROM `posts` WHERE `id` = '" . $_GET['id'] . "'";
    $connectionDB->exec($sql);
    $_SESSION['SuccessMessage'] = "Data Succesfully Deleted!";
    Redirect_To("post.php");
}

?>

