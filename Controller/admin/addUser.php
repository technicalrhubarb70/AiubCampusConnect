<?php
require_once("../../Model/adminModel.php");

if(isset($_POST['add']))
{
    $id       = $_POST['id'];
    $name     = $_POST['name'];
    $gender   = $_POST['gender'];
    $email    = $_POST['email'];

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $role     = $_POST['role'];
    $status   = $_POST['status'];

    $res = addUser($id, $name, $gender, $email, $password, $role, $status);

    if($res === "DUPLICATE_ID"){
        header("Location: ../../View/admin/adminHome.php?error=duplicate_id");
        exit();
    }

    if($res === "DUPLICATE_EMAIL"){
        header("Location: ../../View/admin/adminHome.php?error=duplicate_email");
        exit();
    }

    if($res === true){

        require_once("../../Model/loginModel.php");
        insertDataLogin($id, $password, $role);

        header("Location: ../../View/admin/adminHome.php?success=added");
        exit();
    }

    header("Location: ../../View/admin/adminHome.php?error=failed");
    exit();
}
else{
    header("Location: ../../View/admin/adminHome.php");
    exit();
}
?>
