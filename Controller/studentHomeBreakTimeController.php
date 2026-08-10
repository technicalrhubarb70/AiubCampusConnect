<?php
session_start();
require_once("../Model/setFreeTimeModel.php");

if($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET['viewFreeTime'])){

    $s_Id=$_SESSION['loginId']??"";
    $freeTimesById=getFreeTimeByStudentId($s_Id);

    $individualTimes=[];

    foreach($freeTimesById as $value){
        $day=$value['day'];
        $timesArray=array_map('trim',explode(',',$value['free_times']));

        if(!isset($individualTimes[$day])){
            $individualTimes[$day]=[];
        }

        $individualTimes[$day]=array_merge($individualTimes[$day],$timesArray);
        $individualTimes[$day]=array_values(array_unique($individualTimes[$day]));
    }

    $today=date('l');
    $todayTimes=$individualTimes[$today]??[];

    if(empty($todayTimes)){
        $_SESSION['matchedStudentIds']=[];
        $_SESSION['matchedTutorIds']=[];
        header("Location:../View/student/studentHome.php");
        exit();
    }

    $allFreeTimes=getFreeTimeByDay($today);

    $studentIds=[];
    $tutorIds=[];

    foreach($todayTimes as $timeSlot){
        $timeSlot=trim($timeSlot);

        foreach($allFreeTimes as $entry){
            $freeTimesArray=array_map('trim',explode(',',$entry['free_times']));

            if(in_array($timeSlot,$freeTimesArray)){
                if(!is_null($entry['s_id']) && $entry['s_id']!=$s_Id){
                    $studentIds[]=$entry['s_id'];
                }
                if(!is_null($entry['t_id'])){
                    $tutorIds[]=$entry['t_id'];
                }
            }
        }
    }

    $studentIds=array_values(array_unique($studentIds));
    $tutorIds=array_values(array_unique($tutorIds));

    $_SESSION['matchedStudentIds']=$studentIds;
    $_SESSION['matchedTutorIds']=$tutorIds;

    header("Location:../View/student/studentHome.php");
    exit();
}

?>
