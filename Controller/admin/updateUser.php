<?php
require_once("../../Model/adminModel.php");
require_once("../../Model/loginModel.php");

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

    if(empty($newPass)){
        $finalPass = $old['s_password']; // keep old hash
}
    else{
        $finalPass = password_hash($newPass, PASSWORD_DEFAULT);
        updateLoginPassword($id, $finalPass);
}

    $ok = updateUser($id, $name, $gender, $email, $finalPass, $role, $status);

    if($ok){
        header("Location: ../../View/admin/adminHome.php?success=updated");
    } else {
        echo "Update failed";
    }
}
?>
