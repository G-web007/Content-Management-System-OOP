<?php 
require_once './includes/conn.php';

function Redirect_To($new_Location){
    header("Location:".$new_Location);
    exit;
}

function nameexistornot($username){
    global $connectionDB;
    $sql = "SELECT `username` FROM `admin` WHERE username=:username";
    $stmt = $connectionDB->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $result = $stmt->rowCount();
    if ($result==1) {
        return true;
    } else {
        return false;
    }
}

function login_attempt($uname, $pword){
    global $connectionDB;
    $sql = "SELECT * FROM `admin` WHERE `username` = '$uname' AND `password` = '$pword' LIMIT 1";
    $stmt = $connectionDB->prepare($sql);
    $stmt->bindValue(':username', $uname);
    $stmt->bindValue(':password', $pword);
    $stmt->execute();
    $result = $stmt->rowCount();
    if($result == 1){
        return $found_account = $stmt->fetch();
    } else {
        return null;
    }
}

function confirm_login(){
    if(isset($_SESSION['adminuserId'])){
        return true;
    } else {
        $_SESSION['ErrorMessage'] = "Login Required!";
        Redirect_To("login.php");
    }
}

?>