<?php
session_start();
$s_id="";
$t_id="";

if($_SESSION['role']==2){
    $s_id=$_SESSION['loginId']??"";
}else if($_SESSION['role']==3){
    $t_id=$_SESSION['loginId']??"";
}

if($t_id===""){ $t_id=null; }
if($s_id===""){ $s_id=null; }

require_once("../Model/setFreeTimeModel.php");

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $hasErr=false;
    $dayErr="";

    if(empty($_POST['forSunday']) && empty($_POST['forMonday']) && empty($_POST['forTuesday']) && empty($_POST['forWednesday']) && empty($_POST['forThursday'])){
        $hasErr=true;
        $dayErr="Please select at least one time slot";
    }

    if($hasErr==true){
        header("Location:../View/setFreeTimeView.php?dayErr=".$dayErr);
        exit();
    }else{

        // ✅ If day has selections => UPSERT
        // ✅ If day has no selections => delete that day row (so old checked ones removed)

        if(!empty($_POST['forSunday'])){
            $sundayTimes=implode(",",$_POST['forSunday']);
            upsertFreeTime("Sunday",$sundayTimes,$s_id,$t_id);
        }else{
            deleteFreeTimeByDay("Sunday",$s_id,$t_id);
        }

        if(!empty($_POST['forMonday'])){
            $mondayTimes=implode(",",$_POST['forMonday']);
            upsertFreeTime("Monday",$mondayTimes,$s_id,$t_id);
        }else{
            deleteFreeTimeByDay("Monday",$s_id,$t_id);
        }

        if(!empty($_POST['forTuesday'])){
            $tuesdayTimes=implode(",",$_POST['forTuesday']);
            upsertFreeTime("Tuesday",$tuesdayTimes,$s_id,$t_id);
        }else{
            deleteFreeTimeByDay("Tuesday",$s_id,$t_id);
        }

        if(!empty($_POST['forWednesday'])){
            $wednesdayTimes=implode(",",$_POST['forWednesday']);
            upsertFreeTime("Wednesday",$wednesdayTimes,$s_id,$t_id);
        }else{
            deleteFreeTimeByDay("Wednesday",$s_id,$t_id);
        }

        if(!empty($_POST['forThursday'])){
            $thursdayTimes=implode(",",$_POST['forThursday']);
            upsertFreeTime("Thursday",$thursdayTimes,$s_id,$t_id);
        }else{
            deleteFreeTimeByDay("Thursday",$s_id,$t_id);
        }

        // back to home
        if($_SESSION['role']==2){
            header("Location:../View/student/studentHome.php");
        }else{
            header("Location:../View/teacher/teacherHome.php");
        }
        exit();
    }
}
?>
