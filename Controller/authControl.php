<?php
session_start();
require_once("../Model/loginModel.php");

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $has_Err=false;
    $idErr="";
    $passErr="";
    $userId=$_POST["userId"]??"";
    $pass=$_POST["pass"]??"";

    if(empty($userId))
    {
        $has_Err=true;
        $idErr="user ID cannot be empty";
    }
    else
    {
        if(!preg_match("/^[0-9-]+$/",$userId))
        {
            $has_Err=true;
            $idErr="invalid user ID format";
        }
    }

    if(empty($pass))
    {
        $has_Err=true;
        $passErr="pass word cannot be empty";
    }
    else
    {
        if(strlen($pass)<8||strlen($pass)>15)
        {
            $has_Err=true;
            $passErr="password must be 8-15 characters long";
        }
        else if(!preg_match("/[a-z]/",$pass)||!preg_match("/[A-Z]/",$pass)||!preg_match("/[0-9]/",$pass))
        {
            $has_Err=true;
            $passErr="password must contain 1 uppercase, 1 lowercase and 1 number";
        }
    }

    if($has_Err==true)
    {
        header("Location:../View/loginView.php?idErr=".$idErr."&passErr=".$passErr);
        exit();
    }
    else
    {
        $user=searchLoginUserById($userId);

        if(!$user||!password_verify($pass,$user['login_password']))
        {
            $loginErr="Id or password not found";
            header("Location:../View/loginView.php?loginErr=".$loginErr);
            exit();
        }

        if($user["status"]!=1)
        {
            $loginErr="Id or password not found";
            header("Location:../View/loginView.php?loginErr=".$loginErr);
            exit();
        }

        if($user["role"]==1)
        {
            $_SESSION['loginId']=$user['a_id'];
            $_SESSION['role']=$user['role'];
            header("Location:../View/admin/adminHome.php");
            exit();
        }
        else if($user["role"]==2)
        {
            $_SESSION['loginId']=$user['login_id'];
            $_SESSION['role']=$user['role'];
            header("Location:../View/student/studentHome.php");
            exit();
        }
        else
        {
            $loginErr="Id or password not found";
            header("Location:../View/loginView.php?loginErr=".$loginErr);
            exit();
        }
    }
}
else
{
    header("Location:../View/loginView.php");
    exit();
}
?>
