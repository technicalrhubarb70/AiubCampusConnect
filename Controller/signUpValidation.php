<?php
session_start();
require_once("../Model/mailsend.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hasErr = false;
    $sNameErr = "";
    $sGenderErr = "";
    $sPasswordErr = "";
    $sEmailErr = "";
    $sProPicErr = "";

    $name = $_POST['sName'];
    $gender = $_POST['sGender'];
    $pass = $_POST['sPassword'];
    $email = $_POST['sEmail'];
    $file = $_FILES['sProPic'];
    $path = "";

    if (empty($name)) {
        $sNameErr = "name cannot be empty";
        $hasErr = true;
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $sNameErr = "only letters and white space are allowed.";
            $hasErr = true;
        }
    }

    if (empty($gender)) {
        $sGenderErr = "gender cannot be empty";
        $hasErr = true;
    }

    if (empty($pass)) {
        $sPasswordErr = "password cannot be empty";
        $hasErr = true;
    } else {
        if ((strlen($pass) < 6) || (strlen($pass) > 15)) {
            $sPasswordErr = "password length can not be less than 6 or greater than 15";
            $hasErr = true;
        } else if (!preg_match("/[a-z]/", $pass) || !preg_match("/[A-Z]/", $pass) || !preg_match("/[0-9]/", $pass)) {
            $sPasswordErr = "password must contain at least 1 Capital letter,1 small letter and 1 numeric value.";
            $hasErr = true;
        }
    }

    if (empty($email)) {
        $sEmailErr = "email cannot be empty";
        $hasErr = true;
    } else {
        if (!preg_match("/[0-9]/", $email) || !str_contains($email, "@student.aiub.edu") || (strpos($email, "-") != 2) || (strrpos($email, "-") != 8)) {
            $sEmailErr = "please enter a valid email.";
            $hasErr = true;
        }
    }

    $targetPath = "Resources/default.png";

    if (isset($_FILES["sProPic"]) && $_FILES["sProPic"]["error"] !== UPLOAD_ERR_NO_FILE) {

        $file = $_FILES["sProPic"];

        if ($file["error"] !== UPLOAD_ERR_OK) {
            $sProPicErr = "File upload error";
            $hasErr = true;
        } else {

            $allowedTypes = ["image/jpeg", "image/png"];
            if (!in_array($file["type"], $allowedTypes)) {
                $sProPicErr = "Invalid file type. Only JPG and PNG allowed.";
                $hasErr = true;
            } else {

                // FIX: correct folder name Resources (not Resourses)
                $uploadDir = "../Resources/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = uniqid() . "_" . basename($file["name"]);

                // Store this (relative) in session/DB
                $targetPath = "Resources/" . $fileName;

                // Real filesystem path for moving
                $fullPath = "../" . $targetPath;

                if (!move_uploaded_file($file["tmp_name"], $fullPath)) {
                    $sProPicErr = "Failed to move uploaded file.";
                    $hasErr = true;
                }
            }
        }
    }

    /* =======================
       END FIXED PICTURE UPLOAD
       ======================= */

    if (!$hasErr) {

        $s_id = substr($email, 0, strpos($email, '@'));
        $_SESSION['s_id'] = $s_id;
        $_SESSION['s_name'] = $name;
        $_SESSION['s_gender'] = $gender;
        $_SESSION['real_password'] = $pass;
        $_SESSION['s_password'] = password_hash($pass, PASSWORD_DEFAULT);
        $_SESSION['s_email'] = $email;

        // CHANGED: store relative path (default or uploaded)
        $_SESSION['img_path'] = $targetPath;

        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        $emailSent = sendOtp($email, $otp);
        header("Location:../View/student/emailVerificationView.php");
        exit();

    } else {
        header("Location: ../View/student/signUpView.php?sNameErr=" . $sNameErr .
            "&sGenderErr=" . $sGenderErr .
            "&sPasswordErr=" . $sPasswordErr .
            "&sEmailErr=" . $sEmailErr .
            "&sProPicErr=" . $sProPicErr);
        exit();
    }
}
?>
