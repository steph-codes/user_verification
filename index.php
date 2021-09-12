<?php

//session_start();

require('controllers/authController.php');

//verify User using  token 
if(isset($_GET['token'])) {
    $token = $_GET['token'];
    verifyUser($token);
}

if(isset($_GET['password-token'])) {
    $passwordToken = $_GET['password-token'];
    resetPassword($passwordToken);
}

if(!isset($_SESSION['id'])) {
    header('location: login.php');
    exit();
}


?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Homepage</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="style.css">
        <body>

         <div class="container">
             <div class="row">
                 <div class="col-md-4 offset-md-4 form-div login">
                 <?php if(isset($_SESSION['message'])): ?>
                    <div class="alert <?php echo $_SESSION['alert-class'] ?>">
                        <?php 
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        unset($_SESSION['alert-class']);
                        ?>
                    </div> 
                    <!-- dont do anything if its not set -->
                 <?php endif; ?> 
 
                    <h3>Welcome, <?php echo $_SESSION['username']?></h3>

                    <a href="index.php?logout=1" class="logout">Logout</a>

                    <?php if(!$_SESSION['verified']): ?>
                        <div class="alert alert-warning">
                            You need to verify your account.
                            Sign in to your email account and click on the
                            verification link we just emailed you at 
                            <strong><?php echo $_SESSION['email']?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if($_SESSION['verified']): ?>
                    <button class="btn form-control btn-block bt-lg btn-primary">I am verified!</button>
                   <?php endif; ?>

                 </div>
             </div>
         </div>   
        <script src="" async defer></script>
    </body>
</html> 