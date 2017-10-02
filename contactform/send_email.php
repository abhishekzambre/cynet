<?php

// require ReCaptcha class
require('recaptcha-master/src/autoload.php');

$recaptchaSecret = '6Lc3zzIUAAAAANuXM_s9r6CddMZSzxIijDeU1fX-';

$okMessage = 'Contact form successfully submitted. Thank you, We will get back to you soon!';
$errorMessage = 'There was an error while submitting the form. Please try again later.';

try
{
    if (!empty($_POST)) {

        // validate the ReCaptcha, if something is wrong, we throw an Exception,
        // i.e. code stops executing and goes to catch() block

        if (!isset($_POST['g-recaptcha-response'])) {
            throw new \Exception('ReCaptcha is not set.');
        }

        // do not forget to enter your secret key in the config above
        // from https://www.google.com/recaptcha/admin

        $recaptcha = new \ReCaptcha\ReCaptcha($recaptchaSecret, new \ReCaptcha\RequestMethod\CurlPost());

        // we validate the ReCaptcha field together with the user's IP address

        $response = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);


        if (!$response->isSuccess()) {
            throw new \Exception('ReCaptcha was not validated.');
        }


        // everything went well, we can compose the message, as usually

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

        // send email
        $success = mail($EmailTo, $Subject, $Body, "From:".$email);

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
        $responseArray = array('type' => 'success', 'message' => $okMessage);
    }
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
else {
    echo $responseArray['message'];
}

?>