<?php
session_start();
require_once("../../Model/adminModel.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $user = getUserById($id);

    if(!$user){
        header("Location: ../../View/admin/adminHome.php?error=notfound");
        exit();
    }

    $_SESSION['editUser'] = $user;

    header("Location: ../../View/admin/editUserView.php");
    exit();
}
else
{
    header("Location: ../../View/admin/adminHome.php");
    exit();
}
?>
