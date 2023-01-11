<?php
    include_once "system/backend/config.php";
    session_start();
    //session_destroy();
    $error = "";
    $username = "";
    $password = "";
    if(isset($_SESSION["isLoggedIn"])){
        if($_SESSION["isLoggedIn"] == "true"){
            header("location:main");
            exit();
        }
    }
    if($_POST){

        $username = sanitize($_POST["username"]);
        $password = sanitize($_POST["password"]);
        if($username == ""){
            $error = "*Username field should not be empty!";
        }else if($password == ""){
            $error = "*Password field should not be empty!";
        }else{
            global $conn;
            $table = "account";
            $sql = "SELECT * FROM `$table` WHERE username='$username' && password='$password' && status='active'";
            if($result=mysqli_query($conn,$sql)){
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_array($result);
                    $idx = $row["idx"];
                    $access = $row["access"];
                    $_SESSION["isLoggedIn"] = "true";
                    $_SESSION["access"] = $access;
                    $_SESSION["loginidx"] = $idx;
                    header("location:main");
                    exit();
                }else{
                    $error = "*Username or Password is invalid!";
                }
            }else{
                $error = "*System Error!";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="description" content="" >
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!--Meta Responsive tag-->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="system/plugin/bootstrap/css/bootstrap.min.css">

    <!--Font Awesome-->
    <link rel="stylesheet" href="system/plugin/fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="system/plugin/fontawesome/css/fontawesome.css">

    <title>LabaTech ID Generator | Login</title>
  </head>

  <body style="background-image: url('system/images/login-background.jpg');background-repeat:no-repeat;background-attachment:fixed;background-size:cover;height: 100vh">
    
    <!--Login Wrapper-->

    <div class="container-fluid h-100 opacity-50" style="background:rgba(0, 0, 0, 0.8);">
        <h1 class="text-center mb-5 text-white"><br><br>LabaTech ID Generator</h1>    
        <div class="row">
            <div class="col"></div>
            <div class="col-3 bg-skooltech" align="center">
                <h3 class="mt-4 mb-4 text-white">Welcome Back!</h3>
                <img src="system/images/logo.png" width="150" class="rounded-circle" style="border: 2px solid white;">
            </div>
            <div class="col-3 p-4 bg-white">
                <h3 class="mb-2">Login</h3>
                <small class="text-muted bc-description">Sign in with your credentials</small>
                <form method="post" class="mt-2">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" name="username" value="<?php echo $username;?>" class="form-control mt-0" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" name="password" value="<?php echo $password;?>" class="form-control mt-0" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
                    </div>

                    <div class="form-group">
                        <a href="#">
                            <small class="text-danger font-italic"><?php echo $error;?></small>
                        </a>
                        <input type="submit" class="btn bg-skooltech btn-block p-2 mb-1" value="Login">
                    </div>
                </form>
            </div>
            <div class="col"></div>
        </div>
    </div>    

    <!--Login Wrapper-->

    <!-- Page JavaScript Files-->
    <script src="system/plugin/jquery/js/jquery.min.js"></script>
    <!--Popper JS-->
    <script src="system/plugin/popper/js/popper.min.js"></script>
    <!--Bootstrap-->
    <script src="system/plugin/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>