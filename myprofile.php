<?php

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';
$_SESSION['trackingURL'] = $_SERVER['PHP_SELF'];
confirm_login();

// FETCHING THE EXISTING ADMIN DATA
$adminid = $_SESSION['adminuserId'];
global $connectionDB; 
$sql = "SELECT * FROM `admin` WHERE `id` = '$adminid'";
$stmt = $connectionDB->query($sql);
while ($fetch = $stmt->fetch()) {
    $existingname = $fetch['aname'];
    $existingusername = $fetch['username'];
    $existingheadline = $fetch['aheadline'];
    $existingbio = $fetch['abio'];
    $existingimage = $fetch['aimage'];
}                                       
// END FETCHING THE EXISTING ADMIN DATA

if(isset($_POST['submit'])){
    $aname = $_POST['name'];
    $aheadline = $_POST['headline'];
    $abio = $_POST['bio'];
    $image = $_FILES['image']['name'];
    $target = "img/" . basename($_FILES['image']['name']);

    //VALIDATION
    if(strlen($aheadline) > 30){
        $_SESSION['ErrorMessage'] = "Headline should be less than 30 Characters.";
        Redirect_To('myprofile.php');
    } elseif (strlen($abio) > 500) {
        $_SESSION['ErrorMessage'] = "BIO should be less than 500 Characters.";
        Redirect_To('myprofile.php');
    } else{

        // IMAGE
        global $connectionDB;
        if(!empty($_FILES['image']['name'])){
            $sql = "UPDATE `admin` SET `aname` = '$aname', `aheadline` = '$aheadline', `abio` = '$abio', `aimage` = '$image' WHERE `id` = '$adminid'";

        } else {
            $sql = "UPDATE `admin` SET `aname` = '$aname', `aheadline` = '$aheadline', `abio` = '$abio' WHERE `id` = '$adminid'";
        } 
        
        $execute = $connectionDB->query($sql);
        move_uploaded_file($_FILES['image']['tmp_name'],$target);
        if($execute){
            $_SESSION['SuccessMessage'] ="Details Updated Successfully!";
            Redirect_To("myprofile.php");
        } else {
            $_SESSION['ErrorMessage'] = "Something went wrong Try Again!";
            Redirect_To("myprofile.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Myprofile</title>

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- BOOTSTRAP CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
    <!-- NAVBAR -->
    <?php 
    
        include("./pages/navbar.php");
    
    ?>
    <!-- END NAVBAR -->

    <!-- HEADER -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fas fa-user mr-2" style="color: #27aa27;"></i> @<?php echo $existingusername;?></h1>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    <!-- MAIN -->
    <div class="container py-2 mb-4">
        <div class="row">
            <!-- LEFT SIDE -->
            <div class="col-md-3">
                <div class="card">
                    
                    <div class="card-header bg-dark text-light">
                        <h3><?php echo $existingname;?></h3>
                    </div>
                    <div class="card-body">
                        <img src="img/<?php echo $existingimage;?>" class="block img-fluid mb-3" alt="">
                        <div class="">
                            <?php echo $existingheadline;?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END LEFT SIDE -->

            <!-- RIGHT SIDE -->
            <div class="col-md-9" style="min-height:500px;">
                <?php
                    echo errorMessage();
                    echo SuccessMessage();
                ?>
                <form action="myprofile.php" method="POST" enctype="multipart/form-data">
                    <div class="card bg-dark text-light">
                        <div class="card-header bg-secondary text-light">
                            <h4>Edit Profile</h4>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group mb-3">
                                <input class="form-control" type="text" name="name" id="name" value="" placeholder="Your Name">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="headline" placeholder="Headline">
                                <small class="text-muted">Add Professional Headline</small>
                                <span class="text-danger">Not more than 30 characters</span>
                            </div>
                            <div class="form-group mt-4">
                                <textarea placeholder ="Bio" class="form-control" id="bio" name="bio" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="imageselect" class="mb-3 mt-3">Select Image:</label>
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" id="imageselect" name="image">
                                    <label class="input-group-text" for="imageselect">Upload</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 d-flex mt-3">
                                    <a href="dashboard.php" class="btn btn-warning btn-block flex-fill"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                                </div>
                                <div class="col-lg-6 d-flex mt-3">
                                    <button type="submit" name="submit" class="btn btn-success btn-block flex-fill"><i class="fas fa-check"></i> Publish</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END RIGHT SIDE -->
        </div>
    </div>
    <!-- END MAIN -->

    <!-- FOOTER -->
    <?php 

        include("./pages/footer.php");
    
    ?>
    <!-- END FOOTER -->