<?php
session_start();
require_once("../Model/studentModel.php");
require_once("../Model/mailsend.php");
require_once("../Model/loginModel.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['cancelSignup'])) {
        session_unset();
        session_destroy();
        header("Location:../View/loginView.php");
        exit();
    }

    $hasErr = false;
    $sEmailErr = "";
    $sPasswordErr = "";
    $confirmPasswordErr = "";
    $otpErr = "";

    $email = $_POST['sEmail'] ?? "";
    $pass  = $_POST['sPassword'] ?? "";
    $confirmPassword = $_POST['confirmPassword'] ?? "";
    $otpEnter = $_POST['otp'] ?? "";

    if (empty($email)) {
        $sEmailErr = "email cannot be empty";
        $hasErr = true;
    } else if (
        !preg_match("/[0-9]/", $email) ||
        !str_contains($email, "@student.aiub.edu") ||
        (strpos($email, "-") != 2) ||
        (strrpos($email, "-") != 8)
    ) {
        $sEmailErr = "please enter a valid email.";
        $hasErr = true;
    } else {
        $studentData = getStudentByEmail($email);
        if ($studentData == null) {
            $sEmailErr = "No account with this email.";
            $hasErr = true;
        } else {
            $_SESSION['s_email'] = $email;
            $_SESSION['s_id'] = $studentData['s_id'];
        }
    }

    if (isset($_POST['resendOtp'])) {

        if ($hasErr) {
            header("Location:../View/forgetPasswordView.php?sEmailErr=$sEmailErr");
            exit();
        }

        $_SESSION['otp'] = (string)rand(100000, 999999);
        sendOtp($_SESSION['s_email'], $_SESSION['otp']);

        $otpErr = "OTP has been sent to your email.";
        header("Location:../View/forgetPasswordView.php?otpErr=$otpErr"."&sEmail=".$_SESSION['s_email']);
        exit();
    }

    if (isset($_POST['submit'])) {

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

        if ($confirmPassword !== $pass) {
            $confirmPasswordErr = "Password and Confirm Password miss match.";
            $hasErr = true;
        }

        if (empty($otpEnter)) {
            $otpErr = "OTP cannot be empty";
            $hasErr = true;
        } else if (!preg_match("/^[0-9]{6}$/", $otpEnter)) {
            $otpErr = "OTP must be a 6-digit number.";
            $hasErr = true;
        } else if (!isset($_SESSION['otp'])) {
            $otpErr = "Please press Send OTP first.";
            $hasErr = true;
        } else if ($otpEnter != $_SESSION['otp']) {
            $otpErr = "Invalid OTP. Please try again.";
            $hasErr = true;
        }

        if ($hasErr) {
            header("Location:../View/forgetPasswordView.php?"
                . "sEmailErr=$sEmailErr"
                . "&sPasswordErr=$sPasswordErr"
                . "&confirmPasswordErr=$confirmPasswordErr"
                . "&otpErr=$otpErr"
            );
            exit();
        }
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        updateLoginPassword($_SESSION['s_id'], $hashed);
        updatePassword($_SESSION['s_id'], $hashed);

        sendMessage($_SESSION['s_email'], $_SESSION['s_id'], $pass);

        session_unset();
        session_destroy();

        header("Location:../View/loginView.php");
        exit();
    }

    header("Location:../View/forgetPasswordView.php");
    exit();
}
?>
