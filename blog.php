<?php 

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- BOOTSTRAP CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- NAVBAR -->
    <?php 
    
        include("./pages/blognav.php");
    
    ?>
    <!-- END NAVBAR -->

    <!-- MAIN -->
    <div class="container">
        <div class="row mt-4 mb-4">
            <div class="col-sm-8" style="min-height:40px;">
                <h1>The Complete Responsive CMS Blog</h1>
                <h1 class="lead">PHP CMS Blog by Vincent</h1>
                <?php
                // SEARCH BUTTON
                if(isset($_GET['searchbutton'])){
                    $search = $_GET['search'];
                    $sql = "SELECT * FROM `posts` WHERE `datetime` LIKE :search OR `title` LIKE :search OR `category` LIKE :search OR `post` LIKE :search";
                    $stmt = $connectionDB->prepare($sql);
                    $stmt->bindValue(':search', '%'.$search.'%');
                    $stmt->execute();
                    // QUERY WHEN PAGINATION IS ACTIVE i.e Blog.php?Page=1
                } elseif(isset($_GET['page'])) {
                    $page = $_GET['page'];
                    // IF PAGINATION IS 0 OR LESS THAN 0 OR -1...
                    if($page==0||$page<1){
                        $showpostfrom = 0;
                    } else {
                        $showpostfrom = ($page * 5) - 5;
                    }
                    // END PAGNATION IS 0 OR LESS THAN 0 OR -1
                    $sql = "SELECT * FROM `posts` ORDER BY `id` DESC LIMIT $showpostfrom, 5";
                    $stmt = $connectionDB->query($sql);
                    // QUERY WHEN CATEGORY IS ACTIVE IN THE URL
                } elseif(isset($_GET['category'])) {
                    $category = $_GET['category'];
                    $sql = "SELECT * FROM `posts` WHERE `category` ='$category' ORDER BY `id` DESC ";
                    $stmt = $connectionDB->query($sql);

                } else {
                    // DEFAULT QUERY FOR FETCHING AND SHOWING DATA IN THE POSTS WALL
                    $sql = "SELECT * FROM `posts` ORDER BY `id` DESC LIMIT 0,3";
                    $stmt = $connectionDB->query($sql);
                }
                while($fetch = $stmt->fetch()){
                    $postid = $fetch['id'];
                    $datetime = $fetch['datetime'];
                    $posttitle = $fetch['title'];
                    $postcategory = $fetch['category'];
                    $author = $fetch['author'];
                    $image = $fetch['image'];
                    $postdesc = $fetch['post'];
                
                ?>
                <?php
                    echo errorMessage();
                    echo successMessage();
                ?>
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <img src="upload/<?php echo htmlentities($image);?>" class="img-fluid card-img-top" style="max-height:450px;" alt="Image">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlentities($posttitle);?></h4>
                        <small class="text-muted">Category: <strong><a href="blog.php?category=<?php echo htmlentities($postcategory);?>"><?php echo htmlentities($postcategory);?></a></strong> & Written By: <strong><a href="profile.php?username=<?php echo htmlentities($author)?>"><?php echo htmlentities($author);?></a></strong> On <?php echo htmlentities($datetime);?></small>
                        <span style="float:right;" class="badge bg-black text-light"> Comments
                         <!-- COUNTING THE APPROVE COMMENTS -->
                         <?php
                                global $connectionDB;
                                $sqlapprove = "SELECT COUNT(*) FROM `comments` WHERE `post_id` = '$postid' AND `status` = 'ON'";
                                $stmtapprove = $connectionDB->query($sqlapprove);
                                $fetchrows = $stmtapprove->fetch();
                                $fetchtotal = array_shift($fetchrows);
                                echo $fetchtotal;
                                ?>
                                <!-- END COUNTING THE APPROVE COMMENTS -->
                        </span>
                        <hr>
                        <p class="card-text">
                            <?php 
                                if(strlen($postdesc) > 230){
                                $postdesc = substr($postdesc, 0, 230) . '...';
                                }
                                echo $postdesc;
                            ?>
                        </p>
                        <a href="fullpost.php?id=<?php echo htmlentities($postid);?>" style="float:right"><span class="btn btn-info">Read More>></span></a>
                    </div>
                </div>
                <?php 
                
                    }

                ?>
                <!-- PAGINATION -->
                <nav>
                    <ul class="pagination pagination-md">
                        <!-- CREATING BACKWARD BUTTON -->
                        <?php 
                            // pagination BACKWARD will not shown after counting the total
                            if(isset($page)){
                                if($page>1){    
                        ?>
                        <li class="page-item">
                            <a href="blog.php?page=<?php echo $page-1;?>" class="page-link">&laquo;</a>
                        </li>
                        <?php
                        } }
                        ?>
                        <!-- END CREATING FORWARD BUTTON -->
                       <?php
                       global $connectionDB;
                       $sql = "SELECT COUNT(*) FROM `posts`";
                       $stmt = $connectionDB->query($sql);
                       $rowpagination = $stmt->fetch();
                       $totalposts = array_shift($rowpagination);
                    //    echo $totalposts."<br>";
                       $postpagination = $totalposts / 5;
                       $postpagination = ceil($postpagination);
                    //    echo $postpagination;
                       for ($i = 1; $i <= $postpagination; $i++) {
                        if(isset($page)){
                            // ACTIVE PAGE NUMBER
                            if($i==$page){ ?>
                                <li class="page-item active">
                                        <a href="blog.php?page=<?php echo $i;?>" class="page-link"><?php echo $i;?></a>
                                </li>
                            <?php } else { ?>
                                <li class="page-item">
                                    <a href="blog.php?page=<?php echo $i;?>" class="page-link"><?php echo $i;?></a>
                                </li>
                        <?php  } } } ?>
                        <!-- CREATING FORWARD BUTTON -->
                        <?php 
                            // pagination forward will not shown after counting the total
                            if(isset($page) && !empty($page)){
                                if($page+1<=$postpagination){    
                        ?>
                        <li class="page-item">
                            <a href="blog.php?page=<?php echo $page+1;?>" class="page-link">&raquo;</a>
                        </li>
                        <?php
                        } }
                        ?>
                        <!-- END CREATING FORWARD BUTTON -->
                    </ul>
                </nav>
                <!-- END PAGINATION -->
            </div>
        
            <!-- SIDE AREA -->
            <div class="col-sm-4">
                <div class="card mt-4">
                    <div class="card-body">
                        <img src="img/startblog.PNG" class="d-block img-fluid mb-3" alt="">
                        <div class="text-center">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi minima et quis velit perferendis assumenda unde ea a, molestias totam dignissimos labore expedita sit ipsam laboriosam odit vero dolor vitae?
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header bg-dark text-light">
                        <h1 class="lead">Sign Up</h1>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block text-center text-white" name="button">Join the Forum</button>
                        <button type="button" class="btn btn-danger btn-block text-center text-white" name="button">Login</button>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="" class="form-control" placeholder="Enter your email">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary btn-sm text-center text-white" name="button">Subscribe</button>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                <div class="card-header bg-primary text-light">
                    <h2 class="lead">Categories</h2>
                </div>
                    <div class="card-body">
                        <?php
                        global $connectionDB;
                        $sql = "SELECT * FROM `category` ORDER BY `id` DESC";
                        $stmt = $connectionDB->query($sql);
                        while($fetch = $stmt->fetch()){
                            $categoryid = $fetch['id'];
                            $categorytitle = $fetch['title'];           
                        ?>
                        <a href="blog.php?category=<?php echo $categorytitle;?>" class="text-decoration-none"><span class="heading"><?php echo $categorytitle;?></span></a><br>
                        <?php } ?>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h2 class="lead">Recent Posts</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        global $connectionDB;
                        $sql = "SELECT * FROM `posts` ORDER BY `id` DESC LIMIT 0,5";
                        $stmt = $connectionDB->query($sql);
                        while($fetch = $stmt->fetch()){
                            $postid = $fetch['id'];
                            $posttitle = $fetch['title'];
                            $postdatetime = $fetch['datetime'];
                            $postimage = $fetch['image'];
                        ?>
                        <div class="media">
                            <img src="upload/<?php echo htmlentities($postimage);?>" class="d-block img-fluid align-self-start" alt="" width="90" height="94">
                            <div class="media-body ml-2">
                                <a href="fullpost.php?id=<?php echo htmlentities($postid);?>"><h6 class="lead"><?php echo htmlentities($posttitle);?></h6></a>
                                <p class="small"><?php echo htmlentities($postdatetime);?></p>
                            </div>
                        </div>
                        <hr>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- END SIDE AREA -->
            
        </div>
    </div>
    <!-- END MAIN -->

    
    <!-- FOOTER -->
    <?php 
    
        include("./pages/footer.php");
    
    ?>
    <!-- END FOOTER -->
