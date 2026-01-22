<?php
session_start();
require_once("../Model/studentCourseModel.php");

if(!isset($_SESSION['role'])||$_SESSION['role']!=2){
    exit();
}

$q=trim($_GET['q']??"");
if($q===""){
    exit();
}

$data=searchCourseSuggestions($q);

foreach($data as $c){
    $safe=htmlspecialchars($c,ENT_QUOTES);
    echo "<button type='button' onclick=\"pickCourse('$safe')\">$safe</button><br>";
}
?>
