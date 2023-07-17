<?php 
require_once './includes/conn.php';
require_once './includes/function.php';
require_once './includes/sessions.php';

if(isset($_SESSION['adminuserId'])){
    Redirect_To("dashboard.php");
}

if(isset($_POST['submit'])){
    $uname = $_POST['username'];
    $pword = $_POST['password'];

    if(empty($uname) ||empty($pword)){
        $_SESSION['ErrorMessage'] = "All fields must be filled out";
        Redirect_To('login.php');
    } else {
        $found_account = login_attempt($uname, $pword);
        if($found_account){
            $_SESSION['adminuserId'] = $found_account['id'];
            $_SESSION['adminusername'] = $found_account['username'];
            $_SESSION['adminname'] = $found_account['aname'];
            $_SESSION['SuccessMessage'] = "Welcome Admin " . $_SESSION['adminname'];
            if(isset($_SESSION['trackingURL'])){
                Redirect_To($_SESSION['trackingURL']);
            } else {
                Redirect_To('dashboard.php');
            }
        } else {
            $_SESSION['ErrorMessage'] = "Incorrect Username or Password";
            Redirect_To("login.php");
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
    <title>Login</title>

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
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    <!-- MAIN -->
    <section class="container py-2 mb-4">
        <div class="row">
            <div class="offset-sm-3 col-sm-6" style="min-height:500px;">
                <br><br><br>
                <?php
                    echo errorMessage();
                    echo successMessage();
                ?>
                <div class="card bg-secondary text-light">
                    <div class="card-header">
                        <h4>Wellcome Back!</h4>
                    </div>
                    <div class="card-body bg-dark">
                        <form action="login.php" method="POST">
                            <div class="form-group">
                                <label for="username"><span>Username:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="username">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password"><span>Password:</span></label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-white bg-info"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                            </div>
                            <input type="submit" name="submit" class="btn btn-info btn-block" value="Login">
                        </form>
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