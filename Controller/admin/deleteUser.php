<?php
require_once("../../Model/adminModel.php");

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $ok = deleteUser($id);

    if($ok){
        header("Location: ../../View/admin/adminHome.php");
    }else{
        header("Location: ../../View/admin/adminHome.php?error=deletefailed");
    }
}
else
{
    header("Location: ../../View/admin/adminHome.php");
}
?>
