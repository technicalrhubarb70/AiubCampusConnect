<?php
session_start();
require_once("../Model/studentModel.php");
header("Content-Type: application/json");

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
