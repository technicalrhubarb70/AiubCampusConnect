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

    function getDataByIdPassword($id, $role)
    {
        $conn = dbConnect();

        $id = mysqli_real_escape_string($conn, $id);
        $role = (int)$role;

        $query = "SELECT * FROM student WHERE s_id = '$id' AND role = $role";
        return mysqli_query($conn, $query);
    }

    function getAllData(){
        
        $query="SELECT * FROM student";
        $conn=dbConnect();

        $data=mysqli_query($conn,$query);
        return $data;
    }   

  
    function getStudentById($id)
    {
        $conn = dbConnect();
        $id = mysqli_real_escape_string($conn, $id);

        $query = "SELECT * FROM student WHERE s_id = '$id'";
        return mysqli_query($conn, $query);
}


    function addSkill($s_id, $skill_name)
    {
        $conn = dbConnect();

        $s_id = mysqli_real_escape_string($conn, $s_id);
        $skill_name = mysqli_real_escape_string($conn, $skill_name);

        $q = "SELECT skill_id FROM skills ORDER BY skill_id DESC LIMIT 1";
        $r = mysqli_query($conn, $q);

        $next = 1;

        if ($row = mysqli_fetch_assoc($r)) {
            $num = (int) str_replace("SK_", "", $row['skill_id']);
            $next = $num + 1;
        }

        $skill_id = "SK_" . $next;

        $query = "INSERT INTO skills (skill_id, skill_name, s_id)
                VALUES ('$skill_id', '$skill_name', '$s_id')";

        return mysqli_query($conn, $query);
    }


    function getSkillsByStudent($s_id)
    {
        $conn = dbConnect();

        $s_id = mysqli_real_escape_string($conn, $s_id);

        $query = "SELECT skill_name FROM skills WHERE s_id='$s_id'";
        return mysqli_query($conn, $query);
    }


    function addOfferedCourse($s_id, $course)
    {
        $conn = dbConnect();

        $s_id = mysqli_real_escape_string($conn, $s_id);
        $course = mysqli_real_escape_string($conn, $course);

        $t_id = $s_id;
        $rating = 0;

        $query = "INSERT INTO offered_courses (t_id, course, rattings)
                VALUES ('$t_id', '$course', $rating)";

        return mysqli_query($conn, $query);
    }




    

?>