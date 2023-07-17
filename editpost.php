<?php

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';
confirm_login();

$searchid = $_GET['id'];
if(isset($_POST['submit'])){
    $posttitle = $_POST['posttitle'];
    $category = $_POST['category'];
    $image = $_FILES['image']['name'];
    $target = "upload/" . basename($_FILES['image']['name']);
    $postdesc = $_POST['postdescription'];
    $admin = $_SESSION['adminusername'];
    // TIME
    date_default_timezone_set("Asia/Manila");
    $currentTime = time();
    $dateTime = strftime("%B-%d-%Y %H:%M:%S", $currentTime);

    if(empty($posttitle)){
        $_SESSION['ErrorMessage'] = "Title can't be empty!";
        Redirect_To('editpost.php');
    } elseif(strlen($posttitle) < 2){
        $_SESSION['ErrorMessage'] = "Post Title should be more than 2 Characters.";
        Redirect_To('editpost.php');
    } elseif (strlen($postdesc) > 1000) {
        $_SESSION['ErrorMessage'] = "Post Description should be less than 1000 Characters.";
        Redirect_To('editpost.php');
    } else{

        // IMAGE
        if(!empty($_FILES['image']['name'])){
            //UPDATING POST
            global $connectionDB;
            $sql = "UPDATE `posts` SET `title` = '$posttitle', `category` = '$category', `image` = '$image', `post` = '$postdesc' WHERE `id` = '$searchid'";

        } else {
            $sql = "UPDATE `posts` SET `title` = '$posttitle', `category` = '$category', `post` = '$postdesc' WHERE `id` = '$searchid'";
        }

        
        $execute = $connectionDB->query($sql);
        move_uploaded_file($_FILES['image']['tmp_name'],$target);

        if($execute){
            $_SESSION['SuccessMessage'] ="Post Updated Successfully!";
            Redirect_To("post.php");
        } else {
            $_SESSION['ErrorMessage'] = "Something went wrong Try Again!";
            Redirect_To("post.php");
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
    <title>Edit Post</title>

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
                    <h1><i class="fas fa-edit" style="color: #27aa27;"></i> Edit Post</h1>
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

                // FETCHING DATA FROM DATABASE POSTS
                $sql = "SELECT * FROM `posts` WHERE `id` = '$searchid'";
                $stmt = $connectionDB->query($sql);
                while($fetch = $stmt->fetch()){
                $posttitle = $fetch['title'];
                $postcat = $fetch['category'];
                $postimg = $fetch['image'];
                $postdesc = $fetch['post'];
                
            ?>
            <!-- ERROR MESSAGE -->
                <form action="editpost.php?id=<?php echo $searchid;?>" method="POST" enctype="multipart/form-data">
                    <div class="card bg-secondary text-light mb-3">
                        <div class="card-body bg-dark">
                            <div class="form-group">
                                <label for="title" class="mb-3">Post Title:</label>
                                <input class="form-control" type="text" name="posttitle" id="posttitle" value="<?php echo $posttitle;?>">
                            </div>
                            <div class="mt-3">
                                <label>Existing Category : <span class="text-warning"><?php echo htmlentities($postcat);?></span></label>
                            </div>
                            <div class="input-group mt-3">
                                <label class="input-group-text" for="categorytitle">Choose Category:</label>
                                <select class="form-select" id="categorytitle" name="category">
                                    <!-- FETCHING CATEGORY -->
                                    <option ></option>
                                    <?php

                                    global $connectionDB;
                                    $sql = "SELECT title FROM `category`";
                                    $stmt = $connectionDB->query($sql);
                                    while($fetch = $stmt->fetch()){
                                        $id = $fetch['id'];
                                        $title = $fetch['title'];
                                    
                                    ?>
                                    
                                    <option><?php echo $title;?></option>
                                    <?php 
                                        }
                                    ?>
                                    <!-- END FETCHING CATEGORY -->
                                </select>
                            </div>
                            <div class="mt-3">
                                <label>Existing Image:   
                                    <img src="upload/<?php echo $postimg;?>"  width="100px"; height="100px"; alt="image">
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="imageselect" class="mb-3 mt-3">Select Image:</label>
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control" id="imageselect" name="image">
                                    <label class="input-group-text" for="imageselect">Upload</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="post" class="mb-3">Post:</label>
                                <textarea class="form-control" id="post" name="postdescription" rows="3"><?php echo htmlentities($postdesc);?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 d-flex mt-3">
                                    <a href="dashboard.php" class="btn btn-warning btn-block flex-fill"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                                </div>
                                <div class="col-lg-6 d-flex mt-3">
                                    <button type="submit" name="submit" class="btn btn-success btn-block flex-fill"><i class="fas fa-check"></i> Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php 
                
                    }

                ?>
            </div>
        </div>
    </div>
    <!-- END MAIN -->

    <!-- FOOTER -->
    <?php 

        include("./pages/footer.php");
    
    ?>
    <!-- END FOOTER -->

