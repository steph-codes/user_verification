<?php

session_start();

require('config/db.php');
require_once('emailController.php');
//initialize variables, they could change overtime
$errors = array();
$username = "";
$email = "";

#SIGNUP
//if user clicks on signup button
if (isset($_POST['signup-btn'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['password'];

    //validation
    if (empty($username)) {
        $errors['username'] = " Username required"; 
    }
    //iif ==true /email validation filter valid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Email address is invalid';
    }
    if (empty($email)) {
        $errors['email'] = " Email required"; 
    }
    if (empty($password)) {
        $errors['password'] = " Password required"; 
    }
    if ($password !== $passwordConf) {
        $errors['password'] = "The Two passwords do not Match";
    }

    $emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();

    if ($userCount > 0) {
        $errors['email'] = "Email already exists";
    }

    if (count($error) === 0) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(50)); //generates random string of 100
        $verified = false;

        $sql = "INSERT INTO users (username, email, verified, token, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        //because we are inserting no usage of $result = $stmt->get_result();
        $stmt->bind_param('ssbss', $username, $email, $verified, $token, $password);

        if ($stmt->execute()) {
            //login user, insert_id gives the id of the last inserted to db
            $user_id = $conn->insert_id;
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = $verified;

            //sendVerificationEmail($email, $token){};
            //set flash message
            $_SESSION['message'] = "You are now logged in";
            $_SESSION['alert-class'] = "alert-success";
            header("location: index.php");
            exit();
        }else {
            $errors['Database error'] = "Database error: failed to register";
        }
    }
}


#LOGIN
//if user clicks on Login button
if (isset($_POST['login-btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //validation
    if (empty($username)) {
        $errors['username'] = " Username required"; 
    }  
    if (empty($password)) {
        $errors['password'] = " Password required"; 
    }

    //if the no of errors == 0 then 
    if (count($errors) === 0){

        $sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param('ss',$username, $username); 
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); 
    
        if (password_verify($password, $user['password'])) {
            //if true user has right credentials $user['password'] is the hashed password in the db
            //login user, insert_id gives the id of the last inserted to db
            // this is a select cmd not an insert cmd so we cant fetch id from this ->$user_id = $conn->insert_id;
            //we access the user details from $user array in $user = $result->fetch_assoc();
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = $user['verified'];
            //set flash message
            $_SESSION['message'] = "You are now logged in";
            $_SESSION['alert-class'] = "alert-success";
            header("location: index.php");
            exit();

            }else {
                $errors['login failed'] = "Wrong Credentials";
        }
    }
}

#LOGOUT

if(isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    unset($_SESSION['verified']);
    header('location: login.php');
    exit();
}

//verify user by token
function verifyUser($token){
    global $conn;
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1 ";
    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE users SET verified=1 WHERE token= '$token'";
        $output = mysqli_query($conn, $update_query);
    }
    //or if (output){}
    if(mysqli_query($conn, $update_query)) {
        //log user in
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['verified'] = 1;
        //set flash message
        $_SESSION['message'] = "Your email address was successfully verified";
        $_SESSION['alert-class'] = "alert-success";
        header("location: index.php");
        exit();
    }
    else{
        echo "User not Found";
    }
}

#FORGOT PASSWORD
//if user clicks on submit button of forgot pssword button
if (isset($_POST['forgot-password'])) {
    $email = $_POST['email'];
    //validate email 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'Email address is invalid';
    }
    if (empty($email)) {
        $erros['email'] ='Email required';
    }
    
    if(count($errors) == 0){
        //send link to user by taking the user token from db
        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
        $token = $user['token'];
        sendPasswordResetLink($email, $token);
        header('location: password_message.php');
        exit(0);
    }
}

function resetPassword($token) {
    global $conn;
    $sql = "SELECT * FROM users WHERE token ='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $_SESSION['email'] = $user['email'];
    header('location: reset_password.php');
    exit(0);
}

//if user clicked on reset password button
if(isset($_POST['reset-password-btn'])) {
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    if (empty($password) || empty($passwordConf)) {
        $errors['password'] = " Password required"; 
    }
    if ($password !== $passwordConf) {
        $errors['password'] = "The Two passwords do not Match";
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    //we are getting the email from the user in session when the user cicks on reset password, reset password function is executed and it contains the user email
    $email = $_SESSION['email'];

    if(count($errors)== 0){
        $sql = "UPDATE users SET password='$password' WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if ($result){
            header('location: login.php');
            exit(0);
        }
    }
}
?>