<?php
require_once("../../Model/adminModel.php");
require_once("../../Model/skillsModel.php");
require_once("../../Model/setFreeTimeModel.php");
require_once("../../Model/messageModel.php");
require_once("../../Model/studentModel.php");
require_once("../../Model/loginModel.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $ok = deleteUser($id);
    deleteskillByStudentId($_SESSION['loginId']);
    deleteStudentMessages($_SESSION['loginId']);
    deleteStudentCoursesById($_SESSION['loginId']);
    deleteFreeTime($_SESSION['loginId']);
    deleteStudent($_SESSION['loginId']);
    deleteDataLogin($_SESSION['loginId']);
    if($ok){
        header("Location: ../../View/admin/adminHome.php");
    }else{
        header("Location: ../../View/admin/adminHome.php?error=deletefailed");
    }
}
else
{
    header("Location: ../../View/admin/adminHome.php");
}
?>
