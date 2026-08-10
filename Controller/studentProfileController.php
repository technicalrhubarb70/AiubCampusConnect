<?php
session_start();

require_once("../Model/mailsend.php");
require_once("../Model/skillsModel.php");
require_once("../Model/setFreeTimeModel.php");
require_once("../Model/messageModel.php");
require_once("../Model/studentModel.php");
require_once("../Model/loginModel.php");
require_once("../Model/studentCourseModel.php");

if (!isset($_SESSION['loginId']) || !isset($_SESSION['role'])) {
    header("Location:../View/loginView.php");
    exit();
}

if ($_SESSION['role'] != 2) {
    header("Location:../View/loginView.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hasErr = false;

    $sNameErr = "";
    $sGenderErr = "";
    $sEmailErr = "";
    $sProPicErr = "";

    $name   = $_POST['sName'] ?? "";
    $gender = $_POST['sGender'] ?? "";

    $oldStudentRes = getStudentById($_SESSION['loginId']);
    $oldStudent = mysqli_fetch_assoc($oldStudentRes);

    // keep old picture if no new upload
    $targetPath = $oldStudent['s_propic'] ?? "";   // should be like "Resources/xxx.png"

    // validation
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

    // ===== Upload profile pic if user selected a file =====
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
                // ✅ FIXED folder name
                $uploadDir = "../Resources/";

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = uniqid() . "_" . basename($file["name"]);
                $fullDiskPath = $uploadDir . $fileName;

                if (!move_uploaded_file($file["tmp_name"], $fullDiskPath)) {
                    $sProPicErr = "Failed to move uploaded file.";
                    $hasErr = true;
                } else {
                    // ✅ Save CLEAN web path in DB (so view can load easily)
                    $targetPath = "Resources/" . $fileName;
                }
            }
        }
    }

    // ===== other buttons =====
    if (isset($_POST['passwordUpdate'])) {
        header("Location:../View/changePasswordView.php");
        exit();
    }

    if (isset($_POST['deleteProfile'])) {
        deleteskillByStudentId($_SESSION['loginId']);
        deleteStudentMessages($_SESSION['loginId']);
        deleteStudentCoursesById($_SESSION['loginId']);
        deleteFreeTime($_SESSION['loginId']);
        deleteStudent($_SESSION['loginId']);
        deleteDataLogin($_SESSION['loginId']);
        session_unset();
        session_destroy();
        header("Location:../View/loginView.php");
        exit();
    }

    if (isset($_POST['statusUpdate'])) {
        $s_id = $_SESSION['loginId'];
        $current = $_SESSION['s_status'] ?? 0;
        $newStatus = ($current == 1) ? 0 : 1;
        updateStudentStatus($s_id, $newStatus);
        $_SESSION['s_status'] = $newStatus;
        header("Location:../View/student/studentProfileView.php");
        exit();
    }

    // ===== Update DB =====
    if (!$hasErr) {
        $s_id = $_SESSION['loginId'];

        $_SESSION['s_name'] = $name;
        $_SESSION['s_gender'] = $gender;
        $_SESSION['img_path'] = $targetPath;

        // update DB
        updateData($s_id, $name, $gender, $targetPath);

        header("Location:../View/student/studentProfileView.php");
        exit();
    } else {
        header("Location: ../View/student/studentProfileView.php?sNameErr=" . urlencode($sNameErr) .
            "&sGenderErr=" . urlencode($sGenderErr) .
            "&sEmailErr=" . urlencode($sEmailErr) .
            "&sProPicErr=" . urlencode($sProPicErr));
        exit();
    }
}
?>
