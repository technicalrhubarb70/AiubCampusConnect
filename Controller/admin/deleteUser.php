<?php
session_start();

require_once("../../Model/dbConnect.php");
require_once("../../Model/connectionModel.php");
require_once("../../Model/skillsModel.php");
require_once("../../Model/setFreeTimeModel.php");
require_once("../../Model/messageModel.php");
require_once("../../Model/studentCourseModel.php");
require_once("../../Model/studentModel.php");
require_once("../../Model/loginModel.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $conn = dbConnect();

    // 1️⃣ Delete deepest child tables first
    deleteConnectionsByStudentId($id);
    deleteskillByStudentId($id);
    deleteStudentMessages($id);
    deleteStudentCoursesById($id);
    deleteFreeTime($id);

    // 2️⃣ Delete from student table (child of login)
    $rStudent = mysqli_query($conn,"DELETE FROM student WHERE s_id='$id'");
    if(!$rStudent){
        echo "Student Delete Error: " . mysqli_error($conn);
        exit();
    }

    // 3️⃣ Delete from login table (parent)
    $rLogin = mysqli_query($conn,"DELETE FROM login WHERE login_id='$id'");
    if(!$rLogin){
        echo "Login Delete Error: " . mysqli_error($conn);
        exit();
    }

    header("Location: ../../View/admin/adminHome.php?success=deleted");
    exit();
}
else
{
    header("Location: ../../View/admin/adminHome.php");
    exit();
}
?>
