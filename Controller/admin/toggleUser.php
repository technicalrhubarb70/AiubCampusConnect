<?php
require_once("../../Model/adminModel.php");

if(!isset($_GET['id']) || !isset($_GET['status'])){
    echo "ERR";
    exit();
}

$id = $_GET['id'];
$status = $_GET['status'];

$newStatus = ($status == 1) ? 0 : 1;

$ok = updateUserStatus($id, $newStatus);

if($ok){
    echo $newStatus;
}else{
  
    echo $newStatus;
}
?>
