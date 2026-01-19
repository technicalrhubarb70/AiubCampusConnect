<?php
session_start();
require_once("../Model/loginModel.php");
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $has_Err=false;
    $idErr="";
    $passErr="";
    $userId=$_POST["userId"];
    $pass=$_POST["pass"];

    if(empty($userId))
    {
        $has_Err=true;
        $idErr="user ID cannot be empty";
    }else{
        if(!preg_match("/^[0-9-]+$/",$userId)){
            $has_Err=true;
            $idErr="invalid user ID format";
        }
    }
    if(empty($pass))
    {
        $has_Err=true;
        $passErr="pass word cannot be empty";
    }else{
        if(strlen($pass)<8  ||  strlen($pass)>15){
            $has_Err=true;
            $passErr="password must be at least 8 characters long";
        }else if (!preg_match("/[a-z]/",$pass) || !preg_match("/[A-Z]/",$pass) || !preg_match("/[0-9]/",$pass)){
            $sPasswordErr="password must contain at least 1 Capital letter,1 small letter and 1 numeric value.";
            $hasErr=true;
        }
    }
    if($has_Err==true)
    {
        header("Location:../View/loginView.php?idErr=".$idErr."&passErr=".$passErr);
    }
    else
    {
          $user=searchUser($userId, $pass);
         
         if($user)
         {
            if($user["role"]==1)
            {
                if($user["status"]==1)
                {
                    $_SESSION['userId']=$user['userId'];
                    $_SESSION['role']=$user['role'];

                    //header("Location:../View/admin/adminHome.php");
                }

                else
                {
                $loginErr="Id or password not found";
                //header("Location:../View/loginView.php?loginErr=".$loginErr);
                exit();
                }
                
            }

            if($user["role"]==2)
            {
                
                $_SESSION['userId']=$user['login_id'];
                $_SESSION['role']=$user['role'];
                
                header(header: "Location: ../View/student/studentHome.php");
                exit();
            }

                

            

            if($user["role"]==3)
            {
                if($user["status"]==1)
                {
                        
                    $_SESSION['userId']=$user['userId'];
                    $_SESSION['role']=$user['role'];

                    header("Location:../View/tutor/tutorHome.php");
                    exit();

                }

                else
                {
                    $loginErr="Id or password not found";
                    header("Location:../View/loginView.php?loginErr=".$loginErr);
                    exit();

                }
            }   

         

         else
         {
            $loginErr="Id or password not found";
            header("Location:../View/loginView.php?loginErr=".$loginErr);
            exit();

         }
    }



}}


?>