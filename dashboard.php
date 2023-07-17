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
    <title>Dashboard</title>

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
                    <h1><i class="fas fa-cog" style="color: #27aa27;"></i> Dashboard</h1>
                </div>
                <div class="col-lg-3 d-flex mb-2">
                    <a href="addnewpost.php" class="btn btn-primary btn-block flex-fill"><i class="fas fa-plus"></i> Add New Post</a>
                </div>
                <div class="col-lg-3 d-flex mb-2">
                    <a href="categories.php" class="btn btn-info btn-block flex-fill"><i class="fa fa-list-alt"></i> Add New Category</a>
                </div>
                <div class="col-lg-3 d-flex mb-2">
                    <a href="#" class="btn btn-warning btn-block flex-fill"><i class="fa fa-user-plus"></i> Add New Admin</a>
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
            <?php
                    echo successMessage();
                    echo errorMessage();
                ?>
            <!-- LEFT SIDE AREA -->
            <div class="col-lg-2 d-none d-md-block">
                <div class="card text-center bg-black text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Posts</h1>
                        <h4 class="display-5">
                            <i class="fas fa-magnifying-glass"></i>
                            <!-- CHECK THE TOTAL COUNT OF POSTS -->
                            <?php
                            global $connectionDB;
                            $sql = "SELECT COUNT(*) FROM `posts`";
                            $execute = $connectionDB->query($sql);
                            $fetchrows = $execute->fetch(); //FETCH() is a string it will throw some error if you direclty echo the $fetchrows you need to use array_shift to make it an array
                            $fetchposts = array_shift($fetchrows);
                            echo $fetchposts;
                            ?>
                            <!-- END CHECK THE TOTAL COUNT OF POSTS -->
                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-black text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Categories</h1>
                        <h4 class="display-5">
                            <i class="fas fa-folder"></i>
                            <!-- CHECK THE TOTAL COUNT OF CATEGORIES -->
                            <?php
                            global $connectionDB;
                            $sql = "SELECT COUNT(*) FROM `category`";
                            $execute = $connectionDB->query($sql);
                            $fetchrows = $execute->fetch(); //FETCH() is a string it will throw some error if you direclty echo the $fetchrows you need to use array_shift to make it an array
                            $fetchcategory = array_shift($fetchrows);
                            echo $fetchcategory;
                            ?>
                            <!-- END CHECK THE TOTAL COUNT OF CATEGORIES -->
                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-black text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Admin</h1>
                        <h4 class="display-5">
                            <i class="fas fa-user"></i>
                            <!-- CHECK THE TOTAL COUNT OF ADMIN -->
                            <?php
                            global $connectionDB;
                            $sql = "SELECT COUNT(*) FROM `admin`";
                            $execute = $connectionDB->query($sql);
                            $fetchrows = $execute->fetch(); //FETCH() is a string it will throw some error if you direclty echo the $fetchrows you need to use array_shift to make it an array
                            $fetchadmin = array_shift($fetchrows);
                            echo $fetchadmin;
                            ?>
                            <!-- END CHECK THE TOTAL COUNT OF ADMIN -->
                        </h4>
                    </div>
                </div>
                <div class="card text-center bg-black text-white mb-3">
                    <div class="card-body">
                        <h1 class="lead">Comments</h1>
                        <h4 class="display-5">
                            <i class="fas fa-comment"></i>
                            <!-- CHECK THE TOTAL COUNT OF COMMENTS -->
                            <?php
                            global $connectionDB;
                            $sql = "SELECT COUNT(*) FROM `comments`";
                            $execute = $connectionDB->query($sql);
                            $fetchrows = $execute->fetch(); //FETCH() is a string it will throw some error if you direclty echo the $fetchrows you need to use array_shift to make it an array
                            $fetchcomments = array_shift($fetchrows);
                            echo $fetchcomments;
                            ?>
                            <!-- END CHECK THE TOTAL COUNT OF COMMENTS -->
                        </h4>
                    </div>
                </div>
            </div>
            <!-- END LEFT SIDE AREA -->

            <!-- RIGHT SIDE AREA -->
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h2>Top Posts</h2>
                    </div>
                    <div class="card-body shadow">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Date & Time</th>
                                    <th>Author</th>
                                    <th>Comments</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                global $connectionDB;
                                $sql = "SELECT * FROM `posts` ORDER BY `id` DESC LIMIT 0,5";
                                $stmt = $connectionDB->query($sql);
                                $count = 1;
                                while($fetch = $stmt->fetch()){
                                    $postid = $fetch['id'];
                                    $postdatetime = $fetch['datetime'];
                                    $postauthor = $fetch['author'];
                                    $posttitle = $fetch['title'];
                                    $postcomment = $fetch['post'];
                                ?>
                                <tr>
                                    <td><?php echo $count++;?></td>
                                    <td><?php echo $posttitle;?></td>
                                    <td><?php echo $postdatetime;?></td>
                                    <td><?php echo $postauthor;?></td>
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
                                    <td><a href="fullpost.php?id=<?php echo $postid;?>"><span class="btn btn-info">Preview</span></a></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END RIGHT SIDE AREA -->
        </div>
    </section>
    <!-- END MAIN -->



    <!-- FOOTER -->
    <?php 
    
        include("./pages/footer.php");

    ?>
    <!-- END FOOTER -->
  