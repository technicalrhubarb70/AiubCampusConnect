<?php
session_start();
require_once("../Model/studentCourseModel.php");

if(!isset($_SESSION['role'])||$_SESSION['role']!=2){
    header("Location:../View/loginView.php");
    exit();
}

$s_id=$_SESSION['loginId']??null;

if($s_id==null){
    header("Location:../View/loginView.php");
    exit();
}

$myCourses=getStudentCourse($s_id);
$courseMatches=[];

foreach($myCourses as $course){

    $res=getStudentsByCourse($course,$s_id);

    if($res){
        while($r=mysqli_fetch_assoc($res)){
            $courseMatches[]=[
                'course'=>$course,
                's_id'=>$r['s_id']
            ];
        }
    }
}

$courseMatches=array_map("unserialize",array_unique(array_map("serialize",$courseMatches)));

$_SESSION['course_matches']=$courseMatches;

header("Location:../View/student/studentHome.php");
exit();
?>
