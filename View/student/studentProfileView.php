<?php
session_start();
require_once("../../Model/studentModel.php");
require_once("../../Model/skillsModel.php");
$student = mysqli_fetch_assoc(getStudentById($_SESSION['loginId']));

if ($_SESSION['role'] != 2) {
    header("Location:../loginView.php");
    exit();
}

if(!isset($_SESSION['loginId'])||!isset($_SESSION['role'])){
    header("Location:../loginView.php");
    exit();
}

if (!$student) {
  $student = [
    's_name' => 'Unknown',
    'status' => 0
  ];
}
$_SESSION['s_propic']=$student['s_propic'];
$_SESSION['s_password']=$student['s_password'];
$_SESSION['s_email']=$student['s_email'];
$_SESSION['s_status'] = $student['status'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile| AIUB CampusConnect</title>
</head>
<body>

<h2>Update Account</h2>

<form action="../../Controller/studentProfileController.php" method="POST" enctype="multipart/form-data">

    
        <label>Name</label><br>
        <input type="text" name="sName"
        value="<?=htmlspecialchars($student['s_name']??'')?>">
        <br>
        <?php if(isset($_GET["sNameErr"])) echo $_GET["sNameErr"]; ?>
  

        <label>Gender</label><br>
        <input type="radio" name="sGender" value="male"
        <?=($student['s_gender']??'')==='male'?'checked':''?>>
        Male

        <input type="radio" name="sGender" value="female"
        <?=($student['s_gender']??'')==='female'?'checked':''?>>
        Female
        <br>
        <br>
        <label>Change Profile Picture</label>
        <input type="file" name="sProPic"><br>
        <?php if(isset($_GET["sProPicErr"])) echo $_GET["sProPicErr"]; ?><br>
        <br>
        <label>Status :   </label><?= (($_SESSION['s_status'] ?? 0) == 1) ? "  Active   " : "  Deactive   " ?>
        <input type="submit" name="statusUpdate" value="Change Status"><br><br>

        <input type="submit" name="passwordUpdate" value="Change Password"><br><br>

        <input type="submit" name="deleteProfile" value="Delete Account "><br><br>

    <div>
        <input type="submit" name="submit" value="Update">
        <input type="reset" name="reset" value="Reset">
    </div>

</form>

<br>

<a href="studentHome.php">Go To HOME</a>

</body>
</html>
