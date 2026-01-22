<?php
require_once("dbConnect.php");


function searchUser($key)
{
    $conn = dbConnect();
    return mysqli_query(
        $conn,
        "SELECT s_id, s_name, s_email, role, status
         FROM student
         WHERE s_id LIKE '%$key%'
            OR s_name LIKE '%$key%'
            OR s_email LIKE '%$key%'"
    );
}

function getAllUsers()
{
    $conn = dbConnect();
    return mysqli_query($conn, "SELECT * FROM student");
}

function getUserById($id)
{
    $conn = dbConnect();
    $res = mysqli_query(
        $conn,
        "SELECT s_id, s_name, s_gender, s_email, role, status
         FROM student
         WHERE s_id='$id'"
    );

    if($res && mysqli_num_rows($res) == 1){
        return mysqli_fetch_assoc($res);
    }
    return null;
}

function updateUser($id, $name, $gender, $email, $password, $role, $status)
{
    $conn = dbConnect();

    if($password != ""){
        mysqli_query($conn,
            "UPDATE student
             SET s_name='$name',
                 s_gender='$gender',
                 s_email='$email',
                 s_password='$password',
                 role=$role,
                 status=$status
             WHERE s_id='$id'"
        );

        mysqli_query($conn,
            "UPDATE login
             SET login_password='$password',
                 role=$role,
                 status=$status
             WHERE login_id='$id'"
        );
    }
    else{
        mysqli_query($conn,
            "UPDATE student
             SET s_name='$name',
                 s_gender='$gender',
                 s_email='$email',
                 role=$role,
                 status=$status
             WHERE s_id='$id'"
        );

        mysqli_query($conn,
            "UPDATE login
             SET role=$role,
                 status=$status
             WHERE login_id='$id'"
        );
    }

    return !mysqli_error($conn);
}


function deleteUser($id)
{
    $conn = dbConnect();

    mysqli_query($conn, "DELETE FROM student WHERE s_id='$id'");
    if(mysqli_error($conn)) return false;

    mysqli_query($conn, "DELETE FROM login WHERE login_id='$id'");
    if(mysqli_error($conn)) return false;

    return true;
}


function addUser($id, $name, $gender, $email, $password, $role, $status)
{
    $conn = dbConnect();

    mysqli_query(
        $conn,
        "INSERT INTO login (login_id, login_password, role, status)
         VALUES ('$id', '$password', $role, $status)"
    );

    if(mysqli_errno($conn) == 1062){
        return "DUPLICATE_ID";
    }

    if(mysqli_error($conn)){
        return false;
    }

    mysqli_query(
        $conn,
        "INSERT INTO student
         (s_id, s_name, s_gender, s_email, s_password, role, status)
         VALUES
         ('$id', '$name', '$gender', '$email', '$password', $role, $status)"
    );

    if(mysqli_errno($conn) == 1062){
        return "DUPLICATE_EMAIL";
    }

    if(mysqli_error($conn)){
        return false;
    }

    return true;
}

function updateUserStatus($id, $newStatus)
{
    $conn = dbConnect();
    mysqli_query($conn, "UPDATE student SET status=$newStatus WHERE s_id='$id'");
    return !mysqli_error($conn);
}
?>
