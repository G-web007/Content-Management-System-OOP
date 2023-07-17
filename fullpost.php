<?php 

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

$postidurl = $_GET['id'];
if(isset($_POST['submit'])){
    $commentname = $_POST['commentname'];
    $commentemail = $_POST['commentemail'];
    $commentarea = $_POST['commentarea'];
    // TIME
    date_default_timezone_set("Asia/Manila");
    $currentTime = time();
    $dateTime = strftime("%B-%d-%Y %H:%M:%S", $currentTime);

    if(empty($commentname) || empty($commentemail) || empty($commentarea)){
        $_SESSION['ErrorMessage'] = "All Fields are Required!";
        Redirect_To("fullpost.php?id={$postidurl}");
    } elseif(strlen($commentarea) > 500){
        $_SESSION['ErrorMessage'] = "Comment length should be less than 500 Characters.";
        Redirect_To("fullpost.php?id={$postidurl}");
    } else{
        global $connectionDB;
        $sql = "INSERT INTO `comments` (`datetime`, `name`, `email`, `comment`, `approve`, `status`, `post_id`) VALUES (:datetime, :name, :email, :comment, 'Pending', 'OFF', :post_id)";
        $stmt = $connectionDB->prepare($sql);
        $stmt->bindValue(':datetime', $dateTime);
        $stmt->bindValue(':name', $commentname);
        $stmt->bindValue(':email', $commentemail);
        $stmt->bindValue(':comment', $commentarea);
        $stmt->bindValue(':post_id', $postidurl);
        $execute = $stmt->execute();

        if($execute){
            $_SESSION['SuccessMessage'] = "Comment Succesfully Added";
            Redirect_To("fullpost.php?id={$postidurl}");
        } else {
            $_SESSION['ErrorMessage'] = "Something went wrong Try Again!";
            Redirect_To("fullpost.php?id={$postidurl}");
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
    <title>Full Post</title>

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
                    echo errorMessage();
                    echo successMessage();
                ?>
                <?php
                // SEARCH BUTTON
                if(isset($_GET['searchbutton'])){
                    $search = $_GET['search'];
                    $sql = "SELECT * FROM `posts` WHERE `datetime` LIKE :search OR `title` LIKE :search OR `category` LIKE :search OR `post` LIKE :search";
                    $stmt = $connectionDB->prepare($sql);
                    $stmt->bindValue(':search', '%'.$search.'%');
                    $stmt->execute();
                } else {
                    
                    if(!isset($postidurl)){
                        $_SESSION['ErrorMessage'] = "Bad Request!";
                        Redirect_To('blog.php');
                    }
                    $sql = "SELECT * FROM `posts` WHERE `id` = '$postidurl'";
                    $stmt = $connectionDB->query($sql);
                    $result = $stmt->rowCount();
                    // URL CHECKING THE ID AND IF THERE'S NO "fullpost.php?id=" it will some  error 
                    if($result != 1){
                        $_SESSION['ErrorMessage'] = "Bad Request!";
                        Redirect_To('blog.php?page=1');
                    }
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
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <img src="upload/<?php echo htmlentities($image);?>" class="img-fluid card-img-top" style="max-height:450px;" alt="Image">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlentities($posttitle);?></h4>
                        <small class="text-muted">Category: <strong><a href="blog.php?category=<?php echo htmlentities($postcategory);?>"><?php echo htmlentities($postcategory);?></a></strong> & Written By: <strong><a href="profile.php?username=<?php echo htmlentities($author)?>"><?php echo htmlentities($author);?></a></strong> On <?php echo htmlentities($datetime);?></small>
                        <hr>
                        <p class="card-text">
                            <!-- nl2br helps in the textarea when you write the HTML tag it will run the html tag in the text area -->
                            <?php echo nl2br($postdesc);?>
                        </p>
                    </div>
                </div>
                <?php 
                
                    }

                ?>

                <!-- COMMENTS AREA -->
                <!-- FETCHING THE DATA FROM COMMENTS TABLE -->
                <div>
                    <section style="background-color: #f6f7f9;">
                        <div class="container my-5 py-5">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12 col-lg-10">
                                    <div class="card text-dark">
                                    <h4 class="my-4 mx-4">Comments</h4>
                                    <?php
                                        global $connectionDB;
                                        $sql = "SELECT * FROM `comments` WHERE `post_id` = '$postidurl' AND status = 'ON'";
                                        $stmt = $connectionDB->query($sql);
                                        while ($fetch = $stmt->fetch()) {
                                        $commentdate = $fetch['datetime'];
                                        $commentname = $fetch['name'];
                                        $commentpost = $fetch['comment'];
                                        $commentstatus = $fetch['status'];
                                    ?>
                                        <div class="card-body p-4">
                                            <div class="d-flex flex-start">
                                            <img class="rounded-circle shadow-1-strong me-3"
                                                src="img/comment.png" alt="avatar" width="60"
                                                height="60" />
                                            <div>
                                                <h6 class="fw-bold mb-1"><?php echo $commentname;?></h6>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="mb-0">
                                                        <?php echo $commentdate;?>
                                                        <!-- STATUS IS ON OR NOT -->
                                                        <?php if($commentstatus == 'ON'){
                                                            echo "<span class='badge bg-primary'>Approved</span>";
                                                        } else {
                                                            echo "<span class='badge bg-danger'>Unapprove</span>";
                                                        }
                                                        ?> 
                                                    </p>
                                                </div>
                                                <p class="mb-0"><?php echo $commentpost;?></p>
                                            </div>
                                            </div>
                                           
                                        </div>
                                        <hr class="my-0" />
                                            <?php } ?>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                    </section>
                </div>
                <hr>
                
                <!-- END COMMENTS AREA -->

                <!-- COMMENT FORM -->
                <form class="" action="fullpost.php?id=<?php echo $postidurl;?>" method="POST">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5>MESSAGE</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user fa-2x"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="commentname" placeholder="Name. . ." value="">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope fa-2x"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="commentemail" placeholder="Email. . ." value="">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <textarea name="commentarea" class="form-control" id="" cols="30" rows="10"></textarea>
                            </div>
                            <div class="form-group mt-3">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- END COMMENTS FORM -->
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
  