<?php

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';
$_SESSION['trackingURL'] = $_SERVER['PHP_SELF'];
confirm_login();

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $admin = $_SESSION['adminusername'];
    // TIME
    date_default_timezone_set("Asia/Manila");
    $currentTime = time();
    $dateTime = strftime("%B-%d-%Y %H:%M:%S", $currentTime);

    if(empty($username) ||empty($password) ||empty($cpassword)){
        $_SESSION['ErrorMessage'] = "All Fields must be filled out.";
        Redirect_To('admin.php');
    } elseif(strlen($password) < 3){
        $_SESSION['ErrorMessage'] = "Password should be more than 3 Characters.";
        Redirect_To('admin.php');
    } elseif (strlen($password !== $cpassword)) {
        $_SESSION['ErrorMessage'] = "Password and Confirm Password not match.";
        Redirect_To('admin.php');
    } elseif (nameexistornot($username)) {
        $_SESSION['ErrorMessage'] = "Username Exists. Try Another One!";
        Redirect_To('admin.php');
    } else{ 
    

        $sql = "INSERT INTO `admin` (`datetime`, `username`, `password`, `aname`, `addedby`) VALUES (:datetime, :username, :password, :aname, :addedby)";
        $stmt = $connectionDB->prepare($sql);
        $stmt->bindValue(':datetime', $dateTime);
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':aname', $name);
        $stmt->bindValue(':addedby', $admin);
        $execute = $stmt->execute();

        if($execute){
            $_SESSION['SuccessMessage'] = "New Admin added Successfully";
            Redirect_To("admin.php");
        } else {
            $_SESSION['ErrorMessage'] = "Something went wrong Try Again!";
            Redirect_To("admin.php");
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
    <title>Admin</title>

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
                    <h1><i class="fa fa-user" style="color: #27aa27;"></i> Manage Admin</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    <!-- MAIN -->
    <div class="container py-2 mb-4">
        <div class="row">
            <div class="offset-lg-1 col-lg-10" style="min-height:500px;">
            <!-- ERROR MESSAGE -->
            <?php
                echo errorMessage();
                echo successMessage();
            ?>
            <!-- ERROR MESSAGE -->
                <form action="admin.php" class="" method="POST">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h5 class="text-center">Add New Admin</h5>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="username" class="mb-3"><span class=""> Username:</span></label>
                                <input class="form-control" type="text" name="username" id="username" value="" placeholder="">
                            </div>
                            <div class="form-group mt-3">
                                <label for="name" class="mb-3"><span class=""> Name: <small class="text-muted">(Optional)</small></span></label>
                                <input class="form-control" type="text" name="name" id="name" value="" placeholder="">
                            </div>
                            <div class="form-group mt-3">
                                <label for="password" class="mb-3"><span class=""> Password:</span></label>
                                <input class="form-control" type="password" name="password" id="password" value="" placeholder="">
                            </div>
                            <div class="form-group mt-3">
                                <label for="confirmpassword" class="mb-3"><span class=""> Confirm Password:</span></label>
                                <input class="form-control" type="password" name="cpassword" id="cpassword" value="" placeholder="">
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
                <div class="card mt-5">
                    <div class="card-header">
                        <h4 class="p-2 mb-2 bg-gradient-warning text-warning"><small>Existing Admin</small></h4>
                    </div>
                    <div class="card-body shadow p-3 bg-white rounded">
                    <table class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>Admin Name</th>
                            <th>Added By</th>
                            <th>Date & Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                        global $connectionDB;
                        $sql = "SELECT * FROM `admin` ORDER BY `id` DESC";
                        $execute = $connectionDB->query($sql);
                        $srno = 1;
                        while($fetch = $execute->fetch()){
                            $adminid = $fetch['id'];
                            $adminusername = $fetch['username'];
                            $adminname = $fetch['aname'];
                            $adminaddedby = $fetch['addedby'];
                            $admindatetime = $fetch['datetime'];


                    ?>
                        <tr>
                            <td><?php echo htmlentities($srno++);?></td>
                            <td><?php echo htmlentities($adminusername);?></td>
                            <td><?php echo htmlentities($adminname);?></td>
                            <td><?php echo htmlentities($adminaddedby);?></td>
                            <td><?php echo htmlentities($admindatetime);?></td>
                            <td>
                                <a href="deleteadmin.php?id=<?php echo $adminid;?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php 
                    
                    }
                    ?>
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN -->

    <!-- FOOTER -->
    <?php 
    
        include("./pages/footer.php");
    
    ?>
    <!-- END FOOTER -->
   

    