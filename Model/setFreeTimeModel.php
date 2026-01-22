<?php
    require_once("dbConnect.php");

    function insertDataFreeTime($day,$freeTime,$s_id,$t_id){
        if($t_id==null){
            $query="INSERT INTO free_time ( day,free_times,s_id,t_id) VALUES ('$day','$freeTime','$s_id',NULL)";
            $conn=dbConnect();

            $data=mysqli_query($conn,$query);
        }else{
            $query="INSERT INTO free_time ( day,free_times,s_id,t_id) VALUES ('$day','$freeTime',NULL,'$t_id')";
            $conn=dbConnect();

            $data=mysqli_query($conn,$query);
        }
        
    }
    
    function updateFreeTime($day,$freeTime,$s_id,$t_id){
        $query="UPDATE free_time SET free_times='$freeTime' WHERE day='$day' AND s_id='$s_id' AND t_id='$t_id' ";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }
    function deleteFreeTime($s_id){
        
        $query="DELETE FROM free_time WHERE s_id='$s_id'";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }
    
    function getFreeTimeByStudentId($s_id)
    {
        $query = "SELECT * FROM free_time WHERE s_id='$s_id' ";
        $conn=dbConnect();
        $data=mysqli_query($conn,$query);
        $freeTimes=[];
        if(mysqli_num_rows($data)>0)
        {
            while($rows=mysqli_fetch_assoc($data))
            {
                $freeTimes[]=$rows;
            }
        }

        return $freeTimes;
    }
    function getFreeTimeByTeacherId($t_id)
    {
        $query = "SELECT * FROM free_time WHERE t_id='$t_id' ";
        $conn=dbConnect();
        $data=mysqli_query($conn,$query);
        $freeTimes=[];
        if(mysqli_num_rows($data)>0)
        {
            while($rows=mysqli_fetch_assoc($data))
            {
                $freeTimes[]=$rows;
            }
        }

        return $freeTimes;
    }

    function getByFreeTime($day, $timeSlot) {
        $conn = dbConnect();

        $day = mysqli_real_escape_string($conn, $day);
        $timeSlot = mysqli_real_escape_string($conn, $timeSlot);

        $query = "
            SELECT *
            FROM free_time
            WHERE day='$day'
            AND FIND_IN_SET('$timeSlot', free_times) > 0
        ";

        $data = mysqli_query($conn, $query);

        $results = [];
        if ($data && mysqli_num_rows($data) > 0) {
            while ($row = mysqli_fetch_assoc($data)) {
                $results[] = $row;
            }
        }

        return $results;
    }
 
    function getFreeTimeByDay($day)
    {
        $query = "SELECT * FROM free_time WHERE day='$day' ";
        $conn=dbConnect();
        $data=mysqli_query($conn,$query);
        $freeTimes=[];
        if(mysqli_num_rows($data)>0)
        {
            while($rows=mysqli_fetch_assoc($data))
            {
                $freeTimes[]=$rows;
            }
        }

        return $freeTimes;
    }
   
?>