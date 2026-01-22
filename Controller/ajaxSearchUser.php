<?php
session_start();
require_once("../Model/dbConnect.php");

if(!isset($_SESSION['loginId'])){
    exit();
}

$conn=dbConnect();
$q=trim($_GET['q']??"");
$q=mysqli_real_escape_string($conn,$q);

if($q===""){
    exit();
}
$sql="SELECT s_id AS uid,s_name AS uname FROM student WHERE s_id LIKE '%$q%' OR s_name LIKE '%$q%'LIMIT 10 ";

$res=mysqli_query($conn,$sql);
if(!$res){
    exit();
}

while($row=mysqli_fetch_assoc($res)){
    $id=htmlspecialchars($row['uid']);
    $name=htmlspecialchars($row['uname']);

    echo '<form action="../Controller/messageController.php" method="GET">';
    echo '<input type="hidden" name="peer_receiver_id" value="'.$id.'">';
    echo '<button type="submit">'.$id.' - '.$name.'</button>';
    echo '</form>';
}
