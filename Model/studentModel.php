<?php
    require_once("dbConnect.php");
    function insertData($id, $name, $gender, $email, $password,$created_at, $s_propic){
        
        $query="INSERT INTO student (s_id,s_name,s_gender,s_email,s_password,role,status,created_at,s_propic) VALUES ('$id','$name','$gender','$email','$password',2,1,'$created_at','$s_propic')";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);

        if($data)
        {
            echo "<script>alert('Registration successful.');</script>";
            
        }

        else
        {
            echo "<script>alert('Registration failed.');</script>";
        }
    }

    function updateData($id, $name, $gender, $email, $password,$s_propic){
        
        $query="UPDATE student SET s_name=$name,s_gender=$gender,s_email=$email,s_password=$password,s_propic=$s_propic WHERE s_id=$id";
        $conn=dbConnect();
        $data=mysqli_query($conn,$query);
    }
    function deleteData($id){
        
        $query="DELETE FROM student WHERE s_id=$id";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }

    function updatePassword($id,$password){
        
        $query="UPDATE student SET s_password='$password' WHERE s_id='$id' ";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
    }
    
    function getDataById($id,$role){
        
        $query="SELECT * FROM student WHERE s_id=$id AND role=$role";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
        return $data;
    }

    function getStudentByEmail($email){ 
        $query="SELECT * FROM student WHERE s_email='$email'";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
        $student=mysqli_fetch_assoc($data);
        return $student;
    }

    function getAllData(){
        
        $query="SELECT * FROM student";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
        return $data;
    }   
    

?>