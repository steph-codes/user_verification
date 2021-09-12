<?php  require_once 'controllers/authController.php';?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Forgot Password </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="style.css">
        <body>

         <div class="container">
             <div class="row">
                 <div class="col-md-4 offset-md-4 form-div login">
                    <form action="forgot_password.php" method="post">
                        <h3 class="text-center">Recover your Password</h3>
                        <p>
                            Please enter your email address you used to sign up on this site
                            and we will assist you in recovering your password.
                        </p>

                        <?php if(count($errors) > 0 ):?>
                            <div class="alert alert-danger">
                                <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                       
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control form-control-lg">
                        </div>

                        <div class="form-group">
                            <button type="submit" name="forgot-password" class="btn mt-3 form-control btn-primary btn-block btn-lg">Recover your Password</button>
                        </div>

                    </form> 
                 </div>
             </div>
         </div>   
        <script src="" async defer></script>
    </body>
</html>