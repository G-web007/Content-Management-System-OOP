<?php 

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';
$_SESSION['trackingURL'] = $_SERVER['PHP_SELF'];
confirm_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>

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
                <div class="col-md-12 mb-3">
                    <h1><i class="fas fa-sticky-note" style="color: #27aa27;"></i> Posts</h1>
                </div>
                <div class="col-lg-3 d-flex mb-2">
                    <a href="addnewpost.php" class="btn btn-primary btn-block flex-fill"><i class="fas fa-plus"></i> Add New Post</a>
                </div>
                <div class="col-lg-3 d-flex mb-2">
                    <a href="categories.php" class="btn btn-info btn-block flex-fill"><i class="fa fa-list-alt"></i> Add New Category</a>
                </div>
                <div class="col-lg-3 d-flex mb-2">
                    <a href="admin.php" class="btn btn-warning btn-block flex-fill"><i class="fa fa-user-plus"></i> Add New Admin</a>
                </div>
                <div class="col-lg-3 d-flex mb-2">
                    <a href="comments.php" class="btn btn-success btn-block flex-fill"><i class="fas fa-check"></i> Approve Comments</a>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER -->
    
    <!-- MAIN -->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-lg-12" style="min-height:450px;">
                <?php
                    echo successMessage();
                ?>
                <table class="table table-hover shadow-lg p-3 mb-5 bg-white rounded mt-5 table-bordered table-responsive">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Categories</th>
                            <th>Date&Time</th>
                            <th>Author</th>
                            <th>Banner</th>
                            <th>Comments</th>
                            <th>Action</th>
                            <th>Live Preview</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        global $connectionDB;
                        $sql = "SELECT * FROM `posts`";
                        $stmt = $connectionDB->query($sql);
                        $count = 1;
                        while ($fetch = $stmt->fetch()) {
                            $postid = $fetch['id'];
                            $posttitle = $fetch['title'];
                            $postcategory = $fetch['category'];
                            $postdatetime = $fetch['datetime'];
                            $postauthor = $fetch['author'];
                            $postimage = $fetch['image'];
                            $postcomment = $fetch['post'];
                        ?>
                        <tr>
                            <td><?php echo $count++;?></td>
                            <td>
                                <?php 
                                    //15 character will show in the table
                                if(strlen($posttitle) > 15){
                                    $posttitle = substr($posttitle,0,15).'...';
                                }
                            echo $posttitle;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    //8 character will show in the table
                                if(strlen($postcategory) > 8){
                                    $postcategory = substr($postcategory,0,8).'...';
                                }
                                echo $postcategory;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    //16 character will show in the table
                                if(strlen($postdatetime) > 16){
                                    $postdatetime = substr($postdatetime,0,16).'...';
                                }
                                echo $postdatetime;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    //16 character will show in the table
                                if(strlen($postauthor) > 7){
                                    $postauthor = substr($postauthor,0,7).'...';
                                }
                                echo $postauthor;
                                ?>
                            </td>
                            <td><img src="upload/<?php echo $postimage;?>" width="170px" height="50px;"></td>
                            <td>
                                <!-- COUNTING THE APPROVE COMMENTS -->
                                <?php
                                global $connectionDB;
                                $sqlapprove = "SELECT COUNT(*) FROM `comments` WHERE `post_id` = '$postid' AND `status` = 'ON'";
                                $stmtapprove = $connectionDB->query($sqlapprove);
                                $fetchrows = $stmtapprove->fetch();
                                $fetchtotal = array_shift($fetchrows);
                                // if greater than 0 it will not show the count, if its 0 it will not show the count
                                if($fetchtotal>0){
                                ?>
                                <span class="badge bg-success">
                                    <?php echo $fetchtotal;?>
                                </span>
                                <?php 
                                }
                                ?>
                                <!-- END COUNTING THE APPROVE COMMENTS -->

                                <!-- COUNTING THE DISAPPROVE COMMENTS -->
                                <?php
                                global $connectionDB;
                                $sqldisapprove = "SELECT COUNT(*) FROM `comments` WHERE `post_id` = '$postid' AND `status` = 'OFF'";
                                $stmtdisapprove = $connectionDB->query($sqldisapprove);
                                $fetchrows = $stmtdisapprove->fetch();
                                $fetchtotal = array_shift($fetchrows);
                                // if greater than 0 it will not show the count, if its 0 it will not show the count
                                if($fetchtotal>0){
                                ?>
                                <span class="badge bg-danger">
                                    <?php echo $fetchtotal;?>
                                </span>
                                <?php 
                                }
                                ?>
                                <!-- COUNTING THE DISAPPROVE COMMENTS -->
                            </td>
                            <td class="d-flex">
                                <a href="editpost.php?id=<?php echo $fetch['id'];?>"><span class="btn btn-warning mx-2"><i class="fas fa-edit"></i></span></a>
                                <a href="deletepost.php?id=<?php echo $fetch['id'];?>"><span class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></span></a>
                            </td>
                            <td>
                            <a target="_blank" href="fullpost.php?id=<?php echo $fetch['id'];?>"><span class="btn btn-primary">Live</span></a>
                            </td>
                        </tr>
                        <?php

                            }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- END MAIN -->



    <!-- FOOTER -->
    <?php 
    
        include("./pages/footer.php");

    ?>
    <!-- END FOOTER -->
  