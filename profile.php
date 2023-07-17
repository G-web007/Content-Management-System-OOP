<?php 

require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

$searchusername = $_GET['username'];
global $connectionDB;
$sql = "SELECT aname,aheadline,abio,aimage FROM admin WHERE username=:username";
$stmt = $connectionDB->prepare($sql);
$stmt->bindValue(':username', $searchusername);
$stmt->execute();
$result = $stmt->rowCount();
if($result==1){
    while ($fetch = $stmt->fetch()){
        $existingname = $fetch['aname'];
        $existingheadline = $fetch['aheadline'];
        $existingbio = $fetch['abio'];
        $existingimage = $fetch['aimage'];
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

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

    <!-- HEADER -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1><i class="fas fa-user text-success mr-2" style="color: #27aa27;"></i> <?php echo $existingname;?></h1>
                    <h3><?php echo $existingheadline;?></h3>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    <!-- MAIN -->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="col-md-3">
                <img src="img/<?php echo $existingimage;?>" class="d-block img-fluid mb-3 rounded-circle">
            </div>
            <div class="col-md-9" style="min-height:350px;">
                <div class="card">
                    <div class="card-body">
                        <p class="lead"><?php echo $existingbio;?></p>
                    </div>
                </div>
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