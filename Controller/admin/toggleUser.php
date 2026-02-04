<?php
require_once("../../Model/adminModel.php");
require_once("../../Model/loginModel.php");

if(!isset($_GET['id']) || !isset($_GET['status'])){
    echo "ERR";
    exit();
}

$id = $_GET['id'];
$currentStatus = (int)$_GET['status'];

$newStatus = ($currentStatus === 1) ? 0 : 1;

// Update student table
$ok1 = updateUserStatus($id, $newStatus);

// Update login table
$ok2 = updateStatusLogin($id, $newStatus);

// DEBUG (remove after test)
// echo "Student: ".($ok1?1:0)." Login: ".($ok2?1:0);

if($ok1 && $ok2){
    echo $newStatus;
} else {
    echo "ERR";
}
