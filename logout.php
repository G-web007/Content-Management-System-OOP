<?php 
require_once './includes/function.php';
require_once './includes/sessions.php';
$_SESSION['adminuserId'] = null;
$_SESSION['adminusername'] = null;
$_SESSION['adminname'] = null;
session_destroy();
Redirect_To("login.php");
?>