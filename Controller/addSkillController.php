<?php
session_start();

// auth check
if (!isset($_SESSION['loginId']) || !isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../View/loginView.php");
    exit();
}

// allow model to find dbconnect.php even though it uses require_once("dbconnect.php")
$modelDir = realpath(__DIR__ . "/../Model");
if ($modelDir !== false) {
    set_include_path(get_include_path() . PATH_SEPARATOR . $modelDir);
}

// load model
require_once(__DIR__ . "/../Model/skillsModel.php");

// only accept post
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("invalid request"));
    exit();
}

// read input
$skill = trim($_POST["skill"] ?? "");
if ($skill === "") {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("skill cannot be empty"));
    exit();
}

// (optional) basic validation: allow letters, digits, space, + # . -
if (!preg_match("/^[a-zA-Z0-9 .+#-]{2,30}$/", $skill)) {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("invalid skill format"));
    exit();
}

$s_id = $_SESSION["loginId"];

// generate skill_id (table uses varchar)
$skill_id = uniqid("sk_", true);

// insert using YOUR model function name (lowercase)
$ok = insertskill($skill_id, $skill, $s_id);

if ($ok) {
    // go back to home (updated)
    header("Location: ../View/student/studentHome.php");
    exit();
} else {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("database insert failed"));
    exit();
}
