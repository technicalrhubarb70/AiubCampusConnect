<?php
session_start();
require_once("../Model/setFreeTimeModel.php");

    if($_SERVER["REQUEST_METHOD"]=="GET" && isset($_GET['viewFreeTime'])){

        $s_Id=$_SESSION['loginId'];
        $freeTimesById=getFreeTimeByStudentId($s_Id);
        $individualTimes=[];

        foreach($freeTimesById as $value){
            $day=$value['day'];
            $timesArray=explode(',',$value['free_times']);
            $individualTimes[$day]=$timesArray;
        }
        $today=date('l');
        $todayTimes=$individualTimes[$today];
        if(empty($todayTimes)){
            echo "no free time for today $today.";
        }

        $allFreeTimes=getFreeTimeByDay($today);
        $studentIds=[];
        $tutorIds=[];

        foreach($todayTimes as $timeSlot){
            foreach($allFreeTimes as $entry){
                $freeTimesArray=explode(',',$entry['free_times']);
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
