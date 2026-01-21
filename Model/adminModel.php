<?php
require_once("dbConnect.php");

function insertAdminData($id,$name,$email,$password,$role){
    $query="INSERT INTO admin (a_id,a_name,a_email,a_password,role,status) VALUES ('$id','$name','$email','$password',$role,1)";
    $conn=dbConnect();

        $data=mysqli_query($conn,$query);

        if($data)
        {
            echo "data inserted";
            var_dump($data);   
        }
        else
        {
            echo mysqli_error($conn);
            var_dump($data);
        }
}

function updateAdminData($id,$name,$email,$password,$role,$status){
   
    $query="UPDATE admin SET a_name='$name',a_email='$email',a_password='$password',role=$role,status=$status WHERE a_id='$id'";
     $conn=dbConnect();

    $data=mysqli_query($conn,$query);
}

function deleteAdminById($id){
    $conn=dbConnect();
    $id=mysqli_real_escape_string($conn,$id);
    $query="DELETE FROM admin WHERE a_id='$id'";
    $data=mysqli_query($conn,$query);
}

function getAdminById($id){
    $conn=dbConnect();
    $query="SELECT * FROM admin WHERE a_id='$id' LIMIT 1";
    $data=mysqli_query($conn,$query);
    if(!$data){
        return null;
    }
    $row=mysqli_fetch_assoc($data);
    return $row?$row:null;
}
?>
