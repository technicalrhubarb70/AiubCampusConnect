<?php
session_start();
require_once("../Model/tutorModel.php");

$s_id = $_SESSION['loginId'] ?? null;

if (!$s_id) {
    header("Location:../View/loginView.php");
    exit();
}

/* ADD TUTOR COURSE */
if (isset($_POST['addTutor'])) {

    $course = trim($_POST['course']);
    $helpTypes = $_POST['help_type'] ?? [];

    if ($course !== "" && !empty($helpTypes)) {
        $help_type = implode(", ", $helpTypes);
        addTutorCourse($s_id, $course, $help_type);
    }

    header("Location:../View/student/tutorHomeView.php");
    exit();
}

/* DELETE TUTOR COURSE */
if (isset($_GET['deleteTutor'])) {
    $t_id = $_GET['t_id'];
    deleteTutorCourse($t_id, $s_id);
    header("Location:../View/student/tutorHomeView.php");
    exit();
}
?>