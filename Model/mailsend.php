<?php

require_once("../PHPMailer/src/PHPMailer.php"); 
require_once("../PHPMailer/src/SMTP.php"); 
require_once("../PHPMailer/src/Exception.php"); 

use PHPMailer\PHPMailer\PHPMailer;


function sendOtp($email,$otp){

    $mail = new PHPMailer();

    // SMTP SETTINGS
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rafitrahad28@gmail.com';     // your email
    $mail->Password   = 'atlk opmy gpdn tqip';   // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // EMAIL CONTENT
    $mail->setFrom('rafitrahad28@gmail.com', 'AIUB Campus Connect');
    $mail->addAddress($email);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Your OTP code is: $otp\n\nDo not share this OTP.";
    if($mail->send()) {
        return true;
    } else {
        return false;
    }
}
function sendMessage($email,$id,$password){

    $mail = new PHPMailer();

    // SMTP SETTINGS
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rafitrahad28@gmail.com';     // your email
    $mail->Password   = 'atlk opmy gpdn tqip';   // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // EMAIL CONTENT
    $mail->setFrom('rafitrahad28@gmail.com', 'AIUB Campus Connect');
    $mail->addAddress($email);
    $mail->Subject = 'Your Account Details';
    $mail->Body    = "Your Id is: $id, Password is:$password \n\nDo not share this Id and Password.";
    if($mail->send()) {
        return true;
    } else {
        return false;
    }
}


?>
