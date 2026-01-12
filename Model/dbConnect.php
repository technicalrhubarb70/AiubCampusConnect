<?php
$host="localhost";
$user="root";   
$pass="";
$db_Name="aiubcc_db";
$port="3306";
function dbConnect(){
    global $host;
    global $user;
    global $pass;
    global $db_Name;
    global $port;
    $conn=mysqli_connect($host, $user, $pass, $db_Name, $port);

    if($conn){
        return $conn;
    }
   
}
?>
