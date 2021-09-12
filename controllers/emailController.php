<?php

require_once 'vendor/autoload.php';
require_once ('config/constants.php');

//create transport

$transport = (new Swift_SmtpTransport('smtp.gmail.com',465, 'ssl'))
    ->setUsername(EMAIL)
    ->setPassword(PASSWORD)
; 

//create the mailer using your created Transport
$mailer = new Swift_Mailer($transport);

//create a message


function sendVerificationEmail($userEmail, $token){
    //use php library( already developed reuse able code made )
    global $mailer;
    $body = '<!DOCTYPE html>

    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Verify Email</title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="">
        </head>
        <body>
            <div class="wrapper">
                <p>Thank you for signing up on our website. 
                Please click the link below to verify your email.
                </p>
                <a href="http://localhost/user_registration/index.php?token=' . $token . '">
                Verify Your email address
                </a>
            </div>
            <script src="" async defer></script>
        </body>
    </html>';

    $message = (new Swift_Message('Verify your email'))
    //->setFrom(['johndoe@doe.com' => 'John Doe'])
        ->setFrom(EMAIL)
        ->setTo($userEmail)
        //->setTo['receiver@domain.org', 'other@domain.org' => 'A name'])
        ->setBody($body, 'text/html');
        ;

//Send the  Message
    $result = $mailer->semd($message);
}

function sendPasswordResetLink($userEmail, $token){
    global $mailer;
    $body = '<!DOCTYPE html>

    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Reset your Password</title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="">
        </head>
        <body>
            <div class="wrapper">
                <p>Hello there,
                 Please click on the link below to reset your password.   
                </p>
                <a href="http://localhost/user_registration/index.php?password-token=' . $token . '">
                 Reset your password
                </a>
            </div>
            <script src="" async defer></script>
        </body>
    </html>';

    $message = (new Swift_Message('Reset your Password'))
    //->setFrom(['johndoe@doe.com' => 'John Doe'])
        ->setFrom(EMAIL)
        ->setTo($userEmail)
        //->setTo['receiver@domain.org', 'other@domain.org' => 'A name'])
        ->setBody($body, 'text/html');
        ;

//Send the  Message
    $result = $mailer->semd($message);
} 







// public function index($name, \Swift_Mailer $mailer)
// {
//     $message = (new \Swift_Message('Hello Email'))
//         ->setFrom('send@example.com')
//         ->setTo('recipient@example.com')
//         ->setBody(
//             $this->renderView(
//                 // templates/emails/registration.html.twig
//                 'emails/registration.html.twig',
//                 ['name' => $name]
//             ),
//             'text/html'
//         )

//         // you can remove the following code if you don't define a text version for your emails
//         // ->addPart(
//         //     $this->renderView(
//         //         // templates/emails/registration.txt.twig
//         //         'emails/registration.txt.twig',
//         //         ['name' => $name]
//         //     ),
//         //     'text/plain'
//         // )
//     ;

//     $mailer->send($message);

//     return $this->render(...);
// }
?>