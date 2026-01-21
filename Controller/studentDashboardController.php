<?php
session_start();
require_once("../Model/studentModel.php");
require_once("../Model/messageModel.php");

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === 'GET' && isset($_GET['receiver_id'])) {
    $_SESSION['receiver_id'] = $_GET['receiver_id'];
    header("Location: ../View/messageView.php");
    exit();
}

if (!isset($_SESSION['userId']) || !isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    echo json_encode(["ok"=>false,"msg"=>"Unauthorized"]);
    exit();
}

$action = $_POST["action"] ?? "";
$s_id = $_SESSION["userId"];

if ($action === "addSkill") {
    $skill = trim($_POST["skill"] ?? "");
    if ($skill === "") { echo json_encode(["ok"=>false,"msg"=>"Empty"]); exit(); }

    $ok = addSkill($s_id, $skill);
    echo json_encode(["ok"=>$ok?true:false, "msg"=>$ok?"Skill added":"Skill failed"]);
    exit();
}
echo json_encode(["ok"=>false,"msg"=>"Invalid action"]);
exit();
?>
