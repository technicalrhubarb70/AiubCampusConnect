<?php
session_start();
require_once("../Model/mailsend.php");
require_once("../Model/skillsModel.php");
require_once("../Model/setFreeTimeModel.php");
require_once("../Model/messageModel.php");
require_once("../Model/studentModel.php");
require_once("../Model/loginModel.php");
require_once("../Model/studentCourseModel.php");

if ($_SESSION['role'] != 2) {
    header("Location:../loginView.php");
    exit();
}

if(!isset($_SESSION['loginId'])||!isset($_SESSION['role'])){
    header("Location:../loginView.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"]=="POST"){

    $hasErr=false;
    $sNameErr="";
    $sGenderErr="";
    //$sPasswordErr="";
    $sEmailErr="";
    $sProPicErr="";

    $name=$_POST['sName'];
    $gender=$_POST['sGender'];
    //$pass=$_POST['sPassword'];
    //$email=$_POST['sEmail'];
    $file=$_FILES['sProPic'];
    $path="";

    require_once("../Model/studentModel.php");

    $oldStudent=getStudentById($_SESSION['loginId']);
    $targetPath=$_SESSION['s_propic'] ?? "";
    $fileName="";



    if(empty($name)){
        $sNameErr="name cannot be empty";
        $hasErr=true;
    }else{
        if(!preg_match("/^[a-zA-Z-' ]*$/",$name)){
            $sNameErr="only letters and white space are allowed.";
            $hasErr=true;
        }
    }
    if(empty($gender)){
        $sGenderErr="gender cannot be empty";
        $hasErr=true;
    }
       if(isset($_FILES["sProPic"])&&$_FILES["sProPic"]["error"]!==UPLOAD_ERR_NO_FILE){
    $file=$_FILES["sProPic"];

    if($file["error"]!==UPLOAD_ERR_OK){
        $sProPicErr="File upload error";
        $hasErr=true;
    }else{
        $allowedTypes=["image/jpeg","image/png"];
        if(!in_array($file["type"],$allowedTypes)){
            $sProPicErr="Invalid file type. Only JPG and PNG allowed.";
            $hasErr=true;
        }else{
            $uploadDir="../Resourses/";
            if(!is_dir($uploadDir)){
                mkdir($uploadDir,0755,true);
            }
            $fileName=uniqid()."_".basename($file["name"]);
            $newPath=$uploadDir.$fileName;

            if(!move_uploaded_file($file["tmp_name"],$newPath)){
                $sProPicErr="Failed to move uploaded file.";
                $hasErr=true;
            }else{
                $targetPath=$newPath; 
            }
        }
    }
}

if(isset($_POST['passwordUpdate'])){
     header("Location:../View/changePasswordView.php");
    exit();
}
if(isset($_POST['deleteProfile'])){
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
    if ($current == 1) {
        $newStatus = 0;
    } else {
        $newStatus = 1;
    }
    updateStudentStatus($s_id, $newStatus);
    $_SESSION['s_status'] = $newStatus;
    header("Location:../View/student/studentProfileView.php");
    exit();
}


    if(!$hasErr){

        $s_id=$_SESSION['loginId'];
        $_SESSION['s_id']=$s_id;
        $_SESSION['s_name']=$name;

        $_SESSION['s_gender']=$gender;
        $_SESSION['img_path']=$targetPath;
        updateData($_SESSION['s_id'],$_SESSION['s_name'],$_SESSION['s_gender'],$targetPath);
        
        header("Location:../View/student/studentProfileView.php");
        exit();
    }else{
        header("Location: ../View/student/studentProfileView.php?sNameErr=".$sNameErr."&sGenderErr=".$sGenderErr."&sEmailErr=".$sEmailErr."&sProPicErr=".$sProPicErr);
        exit();
       
    }
}

?>
