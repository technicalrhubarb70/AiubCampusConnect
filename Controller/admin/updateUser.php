<?php
require_once("../../Model/adminModel.php");

if(isset($_POST['updateUser']))
{
    $id     = $_POST['s_id'];
    $name   = $_POST['s_name'];
    $gender = $_POST['s_gender'];
    $email  = $_POST['s_email'];
    $role   = $_POST['role'];
    $status = $_POST['status'];

    $newPass = $_POST['s_password'];

    $old = getUserById($id);
    if($old == null){
        header("Location: ../../View/admin/adminHome.php?error=notfound");
        exit();
    }

    $finalPass = $newPass;
    if($finalPass == ""){
        $finalPass = $old['s_password'];
    }
    $propic = $old['s_propic'];

    $ok = updateUser($id, $name, $gender, $email, $finalPass, $role, $status, $propic);

    if($ok){
        header("Location: ../../View/admin/adminHome.php");
    }else{
        header("Location: ../../View/admin/editUserView.php?id=$id&error=failed");
    }
}
else
{
    header("Location: ../../View/admin/adminHome.php");
}
?>
