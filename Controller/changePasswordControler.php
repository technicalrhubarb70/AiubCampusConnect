<?php
session_start();
require_once("../Model/studentModel.php");
require_once("../Model/mailsend.php");
require_once("../Model/loginModel.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['cancelSignup'])) {
        header("Location:../View/student/studentProfileView.php");
        exit();
    }

    $hasErr = false;
    $sPasswordErr = "";
    $sPasswordErr1= "";
    $confirmPasswordErr = "";

    $pass1  = $_POST['sPassword1'] ?? "";
    $pass  = $_POST['sPassword'] ?? "";
    $confirmPassword = $_POST['confirmPassword'] ?? "";
    $otpEnter = $_POST['otp'] ?? "";
  
    if (isset($_POST['submit'])) {

        if (empty($pass1)) {
            $sPasswordErr1 = "password cannot be empty";
            $hasErr = true;
        }else if (strlen($pass1) < 8 || strlen($pass1) > 15) {
            $sPasswordErr1 = "password must be 8-15 characters long.";
            $hasErr = true;
        } else if (!preg_match("/[a-z]/", $pass1) || !preg_match("/[A-Z]/", $pass1) || !preg_match("/[0-9]/", $pass1)) {
            $sPasswordErr1 = "password must contain at least 1 Capital letter,1 small letter and 1 numeric value.";
            $hasErr = true;
        }
        if (empty($pass)) {
            $sPasswordErr = "password cannot be empty";
            $hasErr = true;
        } else if (strlen($pass) < 8 || strlen($pass) > 15) {
            $sPasswordErr = "password must be 8-15 characters long.";
            $hasErr = true;
        } else if (!preg_match("/[a-z]/", $pass) || !preg_match("/[A-Z]/", $pass) || !preg_match("/[0-9]/", $pass)) {
            $sPasswordErr = "password must contain at least 1 Capital letter,1 small letter and 1 numeric value.";
            $hasErr = true;
        }
       if(!password_verify($pass1,$_SESSION['s_password'])){
            $sPasswordErr1="password mismatch with previous password";
            $hasErr=true;
        }

        if ($confirmPassword !== $pass) {
            $confirmPasswordErr = "Password and Confirm Password miss match.";
            $hasErr = true;
        }
      
        if ($hasErr) {
            header("Location:../View/changePasswordView.php?". "&sPasswordErr1=$sPasswordErr1".  "&sPasswordErr=$sPasswordErr". "&confirmPasswordErr=$confirmPasswordErr");
            exit();
        }
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $id=$_SESSION['loginId'];
        updateLoginPassword($id,$hashed);
        updatePassword($id,$hashed);
        unset($_SESSION['s_password']);
        sendMessage($_SESSION['s_email'], $_SESSION['s_id'], $pass);

        header("Location:../View/student/studentProfileView.php");
        exit();
    }
}
?>
