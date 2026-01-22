<?php
session_start();

if (!isset($_SESSION['loginId']) || !isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    die("unauthorized");
}

require_once(__DIR__ . "/../Model/skillsModel.php");

$s_id = $_SESSION['loginId'];

$_SESSION['skill_matches'] = getmatchedstudentsbyskills($s_id);

header("Location: ../View/student/studentHome.php");
exit();
