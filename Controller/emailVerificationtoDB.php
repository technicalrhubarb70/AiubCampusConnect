<?php
session_start();
require_once("../Model/studentModel.php");
require_once("../Model/mailsend.php");
require_once("../Model/loginModel.php");
if($_SERVER["REQUEST_METHOD"]=="POST"){

    $hasErr=false;
    $otpErr="";
    $otp=$_POST['otp'];
    if(isset($_POST['resendOtp'])){
        $otp= rand(100000, 999999);
        $_SESSION['otp']=$otp;
        $emailSent=sendOtp($_SESSION['s_email'],$otp);
        $otpErr="OTP has been resent to your email.";
        header("Location:../View/student/emailVerificationView.php?otpErr=$otpErr");
    }
    if(isset($_POST['cancelSignup'])){
        session_unset();
        session_destroy();
        header("Location:../View/loginView.php");
        exit();
    }
    if(empty($otp)){
        $otpErr="OTP cannot be empty";
        $hasErr=true;
        header("Location:../View/student/emailVerificationView.php?otpErr=".$otpErr);
    }else{
        if(!preg_match("/^[0-9]{6}$/",$otp)){
            $otpErr="OTP must be a 6-digit number.";
            $hasErr=true;
            header("Location:../View/student/emailVerificationView.php?otpErr=$otpErr");
        }else{
            if($otp==$_SESSION['otp'] && !$hasErr){


                insertDataLogin($_SESSION['s_id'],$_SESSION['s_password'],2);
                insertData($_SESSION['s_id'],$_SESSION['s_name'],$_SESSION['s_gender'],$_SESSION['s_email'],$_SESSION['s_password'],date('Y-m-d H:i:s'), $_SESSION['img_path']);
                move_uploaded_file($_SESSION['img_name'], $_SESSION['img_path']);
                sendMessage($_SESSION['s_email'],$_SESSION['s_id'],$_SESSION['real_password']);
                session_unset();
                session_destroy();
                header("Location:../View/loginView.php");
            }else{
                $otpErr="Invalid OTP. Please try again.";
                header("Location:../View/student/emailVerificationView.php?otpErr=$otpErr");
            }
        }
    }
}


?>