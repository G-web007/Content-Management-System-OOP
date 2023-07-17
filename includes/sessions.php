<?php
session_start();

function errorMessage(){
    if(isset($_SESSION['ErrorMessage'])){
        $output = "<div class=\"alert alert-danger text-center\">";
        $output .= htmlentities($_SESSION['ErrorMessage']);
        $output .= "</div>";
        $_SESSION['ErrorMessage'] = null;
        return $output;
    }
}

function successMessage(){
    if(isset($_SESSION['SuccessMessage'])){
        $output = "<div class=\"alert alert-success text-center\">";
        $output .= htmlentities($_SESSION['SuccessMessage']);
        $output .= "</div>";
        $_SESSION['SuccessMessage'] = null;
        return $output;
    }
}

?>