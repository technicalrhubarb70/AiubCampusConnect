<?php
require_once("dbConnect.php");

function addTutorCourse($s_id, $course, $help_type){
    $conn = dbConnect();

    $sql = "INSERT INTO tutor (s_id, course, help_type, status, created_at)
            VALUES (?, ?, ?, 1, NOW())";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $s_id, $course, $help_type);
    mysqli_stmt_execute($stmt);
}

function getTutorCoursesByStudent($s_id){
    $conn = dbConnect();
    $sql = "SELECT * FROM tutor WHERE s_id = ? AND status = 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $s_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function deleteTutorCourse($t_id, $s_id){
    $conn = dbConnect();
    $sql = "DELETE FROM tutor WHERE t_id = ? AND s_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $t_id, $s_id);
    mysqli_stmt_execute($stmt);
}

function searchTutorByCourse($course){
    $conn = dbConnect();

    $sql = "SELECT DISTINCT s_id, course, help_type
            FROM tutor
            WHERE course LIKE ? AND status = 1";

    $stmt = mysqli_prepare($conn, $sql);
    $like = "%".$course."%";
    mysqli_stmt_bind_param($stmt, "s", $like);
    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function isTutor($s_id){
    $conn = dbConnect();
    $sql = "SELECT 1 FROM tutor WHERE s_id = ? AND status = 1 LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $s_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($res) > 0;
}

function getTutorByTid($t_id){
    $conn = dbConnect();
    $sql = "SELECT * FROM tutor WHERE t_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $t_id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

function getTutorStudentId($t_id){
    $conn = dbConnect();
    $sql = "SELECT s_id FROM tutor WHERE t_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $t_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    return $row['s_id'] ?? null;
}


?>