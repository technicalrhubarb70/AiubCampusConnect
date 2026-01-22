<?php
session_start();
require_once("../Model/studentCourseModel.php");

if($_SESSION['role']!=2){
    header("Location:../View/loginView.php");
    exit();
}

$s_id=$_SESSION['loginId'];

if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["addCourse"])){
    $hasErr=false;
    $course=trim($_POST["course"]);
    $courseErr="";
    if($course==""){
        $courseErr="No course selected.";
         $hasErr=true;
        header("Location:../View/student/studentCourseView.php?courseErr=$courseErr");
        exit();
    }

    if(function_exists("isCourseExists") && isCourseExists($s_id,$course)){
        $courseErr="Course already selected.";
         $hasErr=true;
         header("Location:../View/student/studentCourseView.php?courseErr=$courseErr");
        exit();
    }
    if(!$hasErr){
        addStudentCourse($s_id,$course);
        header("Location:../View/student/studentCourseView.php");
        exit();
    }
    
}

if($_SERVER["REQUEST_METHOD"]==="GET" && isset($_GET["delete"])){

    $course=trim($_GET["course"]??"");

    if($course==""){
        $courseErr="No course selected.";
         $hasErr=true;
        header("Location:../View/student/studentCourseView.php?courseErr=$courseErr");
        exit();
    }

    deleteStudentCourse($s_id,$course);
    header("Location:../View/student/studentCourseView.php");
    exit();
}
?>
