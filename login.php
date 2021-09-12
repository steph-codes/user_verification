<?php  require_once 'controllers/authController.php';?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link rel="stylesheet" href="style.css">
        <body>

         <div class="container">
             <div class="row">
                 <div class="col-md-4 offset-md-4 form-div login">
                    <form action="login.php" method="post">
                        <h3 class="text-center">Login</h3>
                        
                        <?php if(count($errors) > 0 ):?>
                            <div class="alert alert-danger">
                                <?php foreach($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                       

                        <div class="form-group">
                            <label for="username">Username or Email</label>
                            <input type="text" name="username" value="<?php echo $username?>" class="form-control form-control-lg">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg">
                        </div>

                        <div class="form-group">
                            <button type="submit" name="login-btn" class="btn mt-3 form-control btn-primary btn-block btn-lg">Login</button>
                        </div>
                        <p class="text-center mt-2 form-p">Not yet a member? <a href="signup.php">Sign In</a></p>
                        <div style="font-size: 0-.8em; text-align: center;"><a href="forgot_password.php">Forgot your Password?</a></div>
                    </form> 
                 </div>
             </div>
         </div>   
        <script src="" async defer></script>
    </body>
</html>