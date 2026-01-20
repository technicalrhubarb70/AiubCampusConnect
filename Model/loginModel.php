<?php
    require_once("dbConnect.php");

    function insertDataLogin($userId,$password,$role){
        
        $query="INSERT INTO login (login_id,login_password,role,status) VALUES ('$userId', '$password', $role,1)";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }
    function updateLoginPassword($userId,$password){
        
        $query="UPDATE login SET login_password='$password' WHERE login_id='$userId' ";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }
    function updateStatusLogin($userId,$status){
        
        $query="UPDATE login SET status='$status' WHERE login_id='$userId' ";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }
    
    function deleteDataLogin($userId){
        
        $query="DELETE FROM login WHERE login_id='$userId'";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }
    function searchUser($userId, $pass)
    {
        $query = "SELECT * FROM login WHERE login_id='$userId' AND login_password ='$pass'";
        $conn=dbConnect();
        $data=mysqli_query($conn,$query);
        $users=[];
        if(mysqli_num_rows($data)>0)
        {
            while($rows=mysqli_fetch_assoc($data))
            {
                $users=$rows;
            }
        }

        return $users;
    }
     function searchUserById($userId)
    {
        $query = "SELECT * FROM login WHERE login_id='$userId' ";
        $conn=dbConnect();
        $data=mysqli_query($conn,$query);
        $users=[];
        if(mysqli_num_rows($data)>0)
        {
            while($rows=mysqli_fetch_assoc($data))
            {
                $users=$rows;
            }
        }

        return $users;
    }
?>