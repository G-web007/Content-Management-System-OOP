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
    <title>Comments</title>

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <!-- BOOTSTRAP CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    

</head>
<body>
    <!-- NAVBAR -->
    <div style="height:10px; background:rgb(61, 104, 132)"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">CMS</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedCMS">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedCMS">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="myprofile.php" class="nav-link"><i class="fa fa-user" aria-hidden="true"></i> My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="posts.php" class="nav-link">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a href="categories.php" class="nav-link">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a href="admins.php" class="nav-link">Manage Admins</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Comments</a>
                    </li>
                    <li class="nav-item">
                        <a href="blog.php?page=1" class="nav-link">Live blog</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="logout.php" class="nav-link"><i class="fas fa-user-times" aria-hidden="true"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div style="height:10px; background:rgb(61, 104, 132)"></div>
    <!-- END NAVBAR -->

    <!-- HEADER -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fas fa-comments" style="color: #27aa27;"></i> Manage Comments</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    <!-- MAIN -->
    <section class="container py-2 mb-2 mt-4">
        <div class="row" style="min-height:30px;">
            <div class="col-lg-12" style="min-height:400px;">
            <?php
                echo errorMessage();
                echo successMessage();
            ?>
                <!-- APPROVED TABLE -->
                <div class="card">
                    <div class="card-header">
                        <h3>Un-Approved Comments</h3>
                    </div>
                    <div class="card-body shadow">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Date&Time</th>
                                    <th>Comment</th>
                                    <th>Approve</th>
                                    <th>Delete</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                global $connectionDB;
                                $sql = "SELECT * FROM `comments` WHERE `status` = 'OFF' ORDER BY `id` DESC";
                                $execute = $connectionDB->query($sql);
                                $srno = 1;
                                while($fetch = $execute->fetch()){
                                    $commentid = $fetch['id'];
                                    $commentdatetime = $fetch['datetime'];
                                    $commentname = $fetch['name'];
                                    $commentcontent = $fetch['comment'];
                                    $commentpostid = $fetch['post_id'];

                                    // TO CONTROL THE LENGTH OF DATA IN COMMENTS
                                    if(strlen($commentdatetime) > 10 ){
                                    $commentdatetime = substr($commentdatetime, 0, 8) . '...';
                                    }

                                    if(strlen($commentname) > 8 ){
                                        $commentname = substr($commentname, 0, 8) . '...';
                                    }

                                    if(strlen($commentcontent) > 50 ){
                                        $commentcontent = substr($commentcontent, 0, 50) . '...';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($srno++);?></td>
                                    <td><?php echo htmlentities($commentname);?></td>
                                    <td><?php echo htmlentities($commentdatetime);?></td>
                                    <td><?php echo htmlentities($commentcontent);?></td>
                                    <td>
                                        <a href="approvecomment.php?id=<?php echo $commentid;?>" class="btn btn-success">Approve</a>
                                    </td>
                                    <td>
                                        <a href="deletecomment.php?id=<?php echo $commentid;?>" class="btn btn-danger">Delete</a>
                                    </td>
                                    <td>
                                        <a href="fullpost.php?id=<?php echo $commentid;?>" class="btn btn-primary">Live</a>
                                    </td>
                                </tr>
                                <?php 
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END APPROVED TABLE -->
                <br>
                <!-- DISAPPROVED TABLE -->
                <div class="card">
                    <div class="card-header">
                        <h3>Approve Comments</h3>
                    </div>
                    <div class="card-body shadow">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Date&Time</th>
                                    <th>Comment</th>
                                    <th>Revert</th>
                                    <th>Delete</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                global $connectionDB;
                                $sql = "SELECT * FROM `comments` WHERE `status` = 'ON' ORDER BY `id` DESC";
                                $execute = $connectionDB->query($sql);
                                $srno = 1;
                                while($fetch = $execute->fetch()){
                                    $commentid = $fetch['id'];
                                    $commentdatetime = $fetch['datetime'];
                                    $commentname = $fetch['name'];
                                    $commentcontent = $fetch['comment'];
                                    $commentpostid = $fetch['post_id'];

                                    // TO CONTROL THE LENGTH OF DATA IN COMMENTS
                                    if(strlen($commentdatetime) > 10 ){
                                    $commentdatetime = substr($commentdatetime, 0, 8) . '...';
                                    }

                                    if(strlen($commentname) > 8 ){
                                        $commentname = substr($commentname, 0, 8) . '...';
                                    }

                                    if(strlen($commentcontent) > 50 ){
                                        $commentcontent = substr($commentcontent, 0, 50) . '...';
                                    }
                                    ?>
                                <tr>
                                    <td><?php echo htmlentities($srno++);?></td>
                                    <td><?php echo htmlentities($commentname);?></td>
                                    <td><?php echo htmlentities($commentdatetime);?></td>
                                    <td><?php echo htmlentities($commentcontent);?></td>
                                    <td style="min-width:140px;">
                                        <a href="disapprovecomment.php?id=<?php echo $commentid;?>" class="btn btn-warning">disapprove</a>
                                    </td>
                                    <td>
                                        <a href="deletecomment.php?id=<?php echo $commentid;?>" class="btn btn-danger">Delete</a>
                                    </td>
                                    <td>
                                        <a href="fullpost.php?id=<?php echo $commentid;?>" class="btn btn-primary">Live</a>
                                    </td>
                                </tr>
                                <?php 
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END DISAPPROVED TABLE -->
            </div>
        </div>
    </section>
    <!-- END MAIN -->



    <!-- FOOTER -->
    <footer class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="lead text-center">Theme By | Vincent Y. Ygbuhay | <span id="copyright">
                        <script>document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))</script></span> &copy; All right Reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- END FOOTER -->
   

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>