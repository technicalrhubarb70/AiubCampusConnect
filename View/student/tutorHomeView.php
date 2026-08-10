<?php
session_start();
require_once("../../Model/tutorModel.php");

if (!isset($_SESSION['loginId']) || $_SESSION['role'] != 2) {
    header("Location:../loginView.php");
    exit();
}

$s_id = $_SESSION['loginId'];
$tutorCourses = getTutorCoursesByStudent($s_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tutor Setup | AIUB CampusConnect</title>
<link rel="stylesheet" href="studentHome.css">
</head>
<body>
    <div style="margin-bottom:16px;">
        <form action="studentHome.php" method="GET">
            <button type="submit" style="
                padding:6px 14px;
                border-radius:6px;
                border:1px solid #ccc;
                background:#f5f5f5;
                cursor:pointer;
            ">
                ← Back to Home
            </button>
        </form>
    </div>


<h2>Become a Tutor</h2>
<p>Select courses and help type</p>

<form action="../../Controller/tutorController.php" method="POST">
    <label>Course</label><br>
    <input type="text"
           name="course"
           id="courseInput"
           autocomplete="off"
           onkeyup="liveSearchCourse()"
           required>

    <div id="courseResults"></div>

    <br>

    <label>Help Type</label><br>
    <label><input type="checkbox" name="help_type[]" value="Project"> Project</label>
    <label><input type="checkbox" name="help_type[]" value="Exam"> Exam</label>

    <br><br>
    <button type="submit" name="addTutor">Add Tutor Course</button>
</form>

<hr>

<h3>Your Tutor Courses</h3>

<?php if (empty($tutorCourses)) { ?>
    <p>No tutor courses added yet.</p>
<?php } else { ?>
    <ul>
        <?php foreach ($tutorCourses as $t) { ?>
            <li>
                <strong><?= htmlspecialchars($t['course']) ?></strong>
                (<?= htmlspecialchars($t['help_type']) ?>)

                <form action="../../Controller/tutorController.php"
                      method="GET"
                      style="display:inline;">
                    <input type="hidden" name="t_id" value="<?= $t['t_id'] ?>">
                    <button type="submit" name="deleteTutor">Delete</button>
                </form>
            </li>
        <?php } ?>
    </ul>
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
        if(xhr.readyState===4 && xhr.status===200){
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
