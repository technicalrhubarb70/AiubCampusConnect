<?php
session_start();
require_once("../Model/loginModel.php");

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $has_Err=false;
    $idErr="";
    $passErr="";

    $userId = trim($_POST["userId"] ?? "");
    $pass   = $_POST["pass"] ?? "";

    if(empty($userId))
    {
        $has_Err=true;
        $idErr="user ID cannot be empty";
    }
    else if(!preg_match("/^[0-9-]+$/",$userId))
    {
        $has_Err=true;
        $idErr="invalid user ID format";
    }

    if(empty($pass))
    {
        $has_Err=true;
        $passErr="password cannot be empty";
    }

    if($has_Err)
    {
        header("Location:../View/loginView.php?idErr=".$idErr."&passErr=".$passErr);
        exit();
    }

    $user = searchLoginUserById($userId);

    if(!$user || !password_verify($pass,$user['login_password']))
    {
        header("Location:../View/loginView.php?loginErr=Id or password not found");
        exit();
    }

    if($user["status"] != 1)
    {
        header("Location:../View/loginView.php?loginErr=Account inactive");
        exit();
    }

    $_SESSION['loginId'] = $user['login_id'];
    $_SESSION['role']    = $user['role'];

    if($user["role"] == 1)
        header("Location:../View/admin/adminHome.php");
    else if($user["role"] == 2)
        header("Location:../View/student/studentHome.php");
    else
        header("Location:../View/loginView.php?loginErr=Invalid role");

    exit();
}
else
{
    header("Location:../View/loginView.php");
    exit();
}
?>
