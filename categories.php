<?php

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';
$_SESSION['trackingURL'] = $_SERVER['PHP_SELF'];
confirm_login();

if(isset($_POST['submit'])){
    $category = $_POST['categorytitle'];
    $admin = $_SESSION['adminusername'];
    // TIME
    date_default_timezone_set("Asia/Manila");
    $currentTime = time();
    $dateTime = strftime("%B-%d-%Y %H:%M:%S", $currentTime);

    if(empty($category)){
        $_SESSION['ErrorMessage'] = "All Fields must be filled out.";
        Redirect_To('categories.php');
    } elseif(strlen($category) < 2){
        $_SESSION['ErrorMessage'] = "Category Title should be more than 2 Characters.";
        Redirect_To('categories.php');
    } elseif (strlen($category) > 49) {
        $_SESSION['ErrorMessage'] = "Category Title should be less than 50 Characters.";
        Redirect_To('categories.php');
    } else{

        $sql = "INSERT INTO `category` (`title`, `author`, `datetime`) VALUES (:categoryName, :adminName, :datetime)";
        $stmt = $connectionDB->prepare($sql);
        $stmt->bindValue(':categoryName', $category);
        $stmt->bindValue(':adminName', $admin);
        $stmt->bindValue(':datetime', $dateTime);
        $execute = $stmt->execute();

        if($execute){
            $_SESSION['SuccessMessage'] = "Category ID: ". $connectionDB->lastInsertId() . " Succesfully Added";
            Redirect_To("categories.php");
        } else {
            $_SESSION['ErrorMessage'] = "Something went wrong Try Again!";
            Redirect_To("categories.php");
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
    <title>Category</title>

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
                    <h1><i class="fa fa-list-alt" style="color: #27aa27;"></i> Manage Categories</h1>
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
                <form action="categories.php" class="" method="POST">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-header">
                            <h5 class="text-center">Add Category</h5>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title" class="mb-3"><span class="fieldinfo">Category Title:</span></label>
                                <input class="form-control" type="text" name="categorytitle" id="title" value="" placeholder="Type Title Here">
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
                        <h4 class="p-2 mb-2 bg-gradient-warning text-warning"><small>Existing Categorie s</small></h4>
                    </div>
                    <div class="card-body shadow p-3 bg-white rounded">
                    <table class="table table-dark table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>No.</th>
                            <th>Category Name</th>
                            <th>Creator Name</th>
                            <th>Date&Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                        global $connectionDB;
                        $sql = "SELECT * FROM `category` ORDER BY `id` DESC";
                        $execute = $connectionDB->query($sql);
                        $srno = 1;
                        while($fetch = $execute->fetch()){
                            $categoryid = $fetch['id'];
                            $categoryname = $fetch['title'];
                            $categorycontent = $fetch['author'];
                            $categorydatetime = $fetch['datetime'];

                            // TO CONTROL THE LENGTH OF DATA IN COMMENTS
                            if(strlen($categorydatetime) > 30 ){
                            $categorydatetime = substr($categorydatetime, 0, 30) . '...';
                            }

                            if(strlen($categoryname) > 15 ){
                                $categoryname = substr($categoryname, 0, 15) . '...';
                            }

                    ?>
                        <tr>
                            <td><?php echo htmlentities($srno++);?></td>
                            <td><?php echo htmlentities($categoryname);?></td>
                            <td><?php echo htmlentities($categorycontent);?></td>
                            <td><?php echo htmlentities($categorydatetime);?></td>
                            <td>
                                <a href="deletecategory.php?id=<?php echo $categoryid;?>" class="btn btn-danger">Delete</a>
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
   

    