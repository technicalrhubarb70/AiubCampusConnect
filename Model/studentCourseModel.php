<?php
require_once("dbConnect.php");

function addStudentCourse($s_id, $course)
{
    $conn = dbConnect();

    $s_id = mysqli_real_escape_string($conn, $s_id);
    $course = mysqli_real_escape_string($conn, $course);

    $query = "INSERT INTO student_course (course, s_id) VALUES ('$course', '$s_id')";

    return mysqli_query($conn, $query);
}

function deleteStudentCourse($s_id, $course)
{
    $conn = dbConnect();

    $s_id = mysqli_real_escape_string($conn, $s_id);
    $course = mysqli_real_escape_string($conn, $course);

    $query = "DELETE FROM student_course WHERE s_id='$s_id' AND course='$course'";

    return mysqli_query($conn, $query);
}

function isCourseExists($s_id, $course)
{
    $conn = dbConnect();

    $s_id = mysqli_real_escape_string($conn, $s_id);
    $course = mysqli_real_escape_string($conn, $course);

    $query = "SELECT * FROM student_course WHERE s_id='$s_id' AND course='$course'";

    $res = mysqli_query($conn, $query);
    return ($res && mysqli_num_rows($res) > 0);
}

function getStudentCourse($s_id){
    $conn=dbConnect();
    $s_id=mysqli_real_escape_string($conn,$s_id);

    $query="SELECT course FROM student_course WHERE s_id='$s_id'";
    $res=mysqli_query($conn,$query);

    $courses=[];
    if($res){
        while($row=mysqli_fetch_assoc($res)){
            $courses[]=$row['course'];
        }
    }
    return $courses;
}
function getStudentCourses($s_id){
     $conn=dbConnect();
    $query="SELECT course FROM student_course WHERE s_id='$s_id'";
    return mysqli_query($conn,$query);
}

function getStudentsByCourse($course,$s_id){
    $conn=dbConnect();
    $course=mysqli_real_escape_string($conn,$course);
    $s_id=mysqli_real_escape_string($conn,$s_id);
    $query="SELECT s_id FROM student_course WHERE course='$course' AND s_id!='$s_id'";
    return mysqli_query($conn,$query);
}

function getStudentCourseResult($s_id){
    $conn=dbConnect();
    $s_id=mysqli_real_escape_string($conn,$s_id);
    $query="SELECT course FROM student_course WHERE s_id='$s_id'";
    return mysqli_query($conn,$query);
}

function searchCourseSuggestions($q){
    $conn=dbConnect();
    $q=mysqli_real_escape_string($conn,$q);
    $query="SELECT course FROM courses WHERE course LIKE '%$q%' LIMIT 10";
    $res=mysqli_query($conn,$query);
    $data=[];
    if($res){
        while($row=mysqli_fetch_assoc($res)){
            $data[]=$row['course'];
        }
    }
    return $data;
}
function deleteStudentCoursesById($s_id){
    $conn=dbConnect();
    $s_id=mysqli_real_escape_string($conn,$s_id);
    $query="DELETE FROM student_course WHERE s_id='$s_id'";
    return mysqli_query($conn,$query);
}



?>
