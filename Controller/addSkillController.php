<?php
session_start();

if (!isset($_SESSION['loginId']) || !isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("Location: ../View/loginView.php");
    exit();
}

$modelDir = realpath(__DIR__ . "/../Model");
if ($modelDir !== false) {
    set_include_path(get_include_path() . PATH_SEPARATOR . $modelDir);
}

require_once(__DIR__ . "/../Model/skillsModel.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("invalid request"));
    exit();
}

$skill = trim($_POST["skill"] ?? "");
if ($skill === "") {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("skill cannot be empty"));
    exit();
}

if (!preg_match("/^[a-zA-Z0-9 .+#-]{2,30}$/", $skill)) {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("invalid skill format"));
    exit();
}

$s_id = $_SESSION["loginId"];

$skill_id = uniqid("sk_", true);

$ok = insertskill($skill_id, $skill, $s_id);

if ($ok) {
    header("Location: ../View/student/studentHome.php");
    exit();
} else {
    header("Location: ../View/student/addSkillView.php?err=" . urlencode("database insert failed"));
    exit();
}
