<?php

// require ReCaptcha class
require('recaptcha-master/src/autoload.php');

$recaptchaSecret = '6Lc3zzIUAAAAANuXM_s9r6CddMZSzxIijDeU1fX-';

$errorMSG = "";

// NAME
if (empty($_POST["name"])) {
    $errorMSG = "Name is required ";
} else {
    $name = $_POST["name"];
}

// EMAIL
if (empty($_POST["email"])) {
    $errorMSG .= "Email is required ";
} else {
    $email = $_POST["email"];
}

// SUBJECT
if (empty($_POST["subject"])) {
    $errorMSG .= "Subject is required ";
} else {
    $subject = $_POST["subject"];
}

// MESSAGE
if (empty($_POST["message"])) {
    $errorMSG .= "Message is required ";
} else {
    $message = $_POST["message"];
}

// CAPTCHA
if (empty($_POST["captcha_response"])) {
    $errorMSG .= "Please check the captcha form ";
} else {
    $captcha_response = $_POST["captcha_response"];
}


$EmailTo = "abhishek.zambre@gmail.com";
$Subject = "CyNET Enquiry: ";
$Subject .= $subject;

// prepare email body text
$Body = "";
$Body .= "Name: ";
$Body .= $name;
$Body .= "\n";
$Body .= "Email: ";
$Body .= $email;
$Body .= "\n";
$Body .= "Message: ";
$Body .= $message;
$Body .= "\n";


$ip = $_SERVER['REMOTE_ADDR'];
$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".recaptchaSecret."&response=".captcha_response."&remoteip=".$ip);
$responseKeys = json_decode($response,true);

if(intval($responseKeys["success"]) !== 1) {
          $success = "";
} else {
         // send email
         $success = mail($EmailTo, $Subject, $Body, "From:".$email);
}

// redirect to success page
if ($success && $errorMSG == ""){
   echo "success";
}else{
    if($errorMSG == ""){
        echo "Something went wrong :(";
    } else {
        echo $errorMSG;
    }
}

?>