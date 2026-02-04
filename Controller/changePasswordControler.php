<?php
session_start();

require_once("../Model/studentModel.php");
require_once("../Model/loginModel.php");
require_once("../Model/mailsend.php");

if (!isset($_SESSION['loginId'])) {
    header("Location:../View/loginView.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['cancelSignup'])) {
        header("Location:../View/student/studentProfileView.php");
        exit();
    }

    $hasErr = false;
    $sPasswordErr1 = "";
    $sPasswordErr  = "";
    $confirmPasswordErr = "";

    $oldPass = $_POST['sPassword1'] ?? "";     // OLD password
    $newPass = $_POST['sPassword'] ?? "";      // NEW password
    $confirmPassword = $_POST['confirmPassword'] ?? "";

    if (isset($_POST['submit'])) {

        // ===== Validate OLD password field =====
        if (empty($oldPass)) {
            $sPasswordErr1 = "Old password cannot be empty";
            $hasErr = true;
        }

        // ===== Validate NEW password field =====
        if (empty($newPass)) {
            $sPasswordErr = "New password cannot be empty";
            $hasErr = true;
        } else if (strlen($newPass) < 8 || strlen($newPass) > 15) {
            $sPasswordErr = "New password must be 8-15 characters long.";
            $hasErr = true;
        } else if (!preg_match("/[a-z]/", $newPass) || !preg_match("/[A-Z]/", $newPass) || !preg_match("/[0-9]/", $newPass)) {
            $sPasswordErr = "New password must contain at least 1 Capital letter, 1 small letter and 1 numeric value.";
            $hasErr = true;
        }

        // ===== Confirm password =====
        if ($confirmPassword !== $newPass) {
            $confirmPasswordErr = "Password and Confirm Password mismatch.";
            $hasErr = true;
        }

        // ===== Verify OLD password using DB (NOT session) =====
        $id = $_SESSION['loginId'];
        $loginUser = searchLoginUserById($id);

        if (!$loginUser || empty($loginUser['login_password'])) {
            $sPasswordErr1 = "User not found or password not set.";
            $hasErr = true;
        } else {
            $currentHash = $loginUser['login_password'];

            if (!password_verify($oldPass, $currentHash)) {
                $sPasswordErr1 = "Old password is incorrect.";
                $hasErr = true;
            }
        }

        if ($hasErr) {
            header("Location:../View/changePasswordView.php"
                . "?sPasswordErr1=" . urlencode($sPasswordErr1)
                . "&sPasswordErr=" . urlencode($sPasswordErr)
                . "&confirmPasswordErr=" . urlencode($confirmPasswordErr)
            );
            exit();
        }

        // ===== Update password =====
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);

        // IMPORTANT: update functions should ideally return true/false.
        updateLoginPassword($id, $hashed);
        updatePassword($id, $hashed);

        // optional: email user new password (not recommended to email plain passwords)
        // sendMessage($_SESSION['s_email'], $_SESSION['s_id'], $newPass);

        header("Location:../View/student/studentProfileView.php");
        exit();
    }
}
?>
