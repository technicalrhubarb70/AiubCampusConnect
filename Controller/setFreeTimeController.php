<?php
session_start();
$s_id="";
$t_id="";
if($_SESSION['role']==2){   
    $s_id=$_SESSION['loginId']??"";
}/*else if($_SESSION['role']==3){
    $t_id=$_SESSION['loginId']??"";
}*/
if($t_id===""){
        $t_id=null;
    }
    if($s_id===""){
        $s_id=null;
}
require_once("../Model/setFreeTimeModel.php");
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $hasErr=false;
    $dayErr="";
    
    if(empty($_POST['forSunday']) && empty($_POST['forMonday']) && empty($_POST['forTuesday']) && empty($_POST['forWednesday']) &&empty($_POST['forThursday']) ){
        $hasErr=true;
        $dayErr="Please select at least one time slot";
    }
    
    if($hasErr==true){
        header("Location:../View/setFreeTimeView.php?dayErr=".$dayErr);
        exit();
    }else{
        if(!empty($_POST['forSunday'])){
            $sundayTimes=implode(",",$_POST['forSunday']);
            insertDataFreeTime("Sunday",$sundayTimes,$s_id,$t_id);
        }
        if(!empty($_POST['forMonday'])){
            $mondayTimes=implode(",",$_POST['forMonday']);
            insertDataFreeTime("Monday",$mondayTimes,$s_id,$t_id);
        }
        if(!empty($_POST['forTuesday'])){
            $tuesdayTimes=implode(",",$_POST['forTuesday']);
            insertDataFreeTime("Tuesday",$tuesdayTimes,$s_id,$t_id);
        }
        if(!empty($_POST['forWednesday'])){
            $wednesdayTimes=implode(",",$_POST['forWednesday']);
            insertDataFreeTime("Wednesday",$wednesdayTimes,$s_id,$t_id);
        }
        if(!empty($_POST['forThursday'])){
            $thursdayTimes=implode(",",$_POST['forThursday']);
            insertDataFreeTime("Thursday",$thursdayTimes,$s_id,$t_id);
        }
        header("Location:../View/student/studentHome.php");
        exit();
    }
   
}
 


?>