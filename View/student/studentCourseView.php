<?php
session_start();
require_once("../../Model/studentCourseModel.php");

if ( $_SESSION['role'] != 2) {
    header("Location:../loginView.php");
    exit();
}

$s_id = $_SESSION['loginId'];

if ($s_id == null) {
    header("Location:../loginView.php");
    exit();
}

$coursesRes=getStudentCourseResult($s_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Courses | AIUB CampusConnect</title>
</head>
<body>

<h2>My Courses</h2>
<p>Student ID: <?= htmlspecialchars($s_id) ?></p>

<form action="../../Controller/studentCourseController.php" method="POST">
  <input type="text" name="course" id="courseInput" maxlength="100" autocomplete="off" onkeyup="liveSearchCourse()" required><input type="submit" name="addCourse" value="Add Course">
<div id="courseResults"></div>
</form>

<h2>Course List</h2>
<?php foreach($coursesRes as $course){ ?>
  <li>
    <?= htmlspecialchars($course['course']) ?>
    <form action="../../Controller/studentCourseController.php" method="GET" style="display:inline;">
      <input type="hidden" name="course" value="<?= htmlspecialchars($course['course']) ?>">
      <input type="submit" name="delete" value="Delete">
    </form>
  </li>
<?php } ?>

<script>
function liveSearchCourse(){
    let q=document.getElementById("courseInput").value;
    let box=document.getElementById("courseResults");

    if(q.length===0){
        box.innerHTML="";
        return;
    }

    let xhr=new XMLHttpRequest();
    xhr.open("GET","../../Controller/ajaxSearchCourse.php?q="+encodeURIComponent(q),true);

    xhr.onreadystatechange=function(){
        if(xhr.readyState===4&&xhr.status===200){
            box.innerHTML=xhr.responseText;
        }
    };

    xhr.send();
}

function pickCourse(val){
    document.getElementById("courseInput").value=val;
    document.getElementById("courseResults").innerHTML="";
}
</script>


</body>
</html>
