<?php
session_start();
$s_id="";
$t_id="";
if($_SESSION['role']==2){   
    $s_id=$_SESSION['loginId']??"";
}else if($_SESSION['role']==3){
    $t_id=$_SESSION['loginId']??"";
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Free Time | AIUB CampusConnect</title>
<link rel="stylesheet" href="student/signup.css">
</head>
<body>

<form method="post" action="../Controller/setFreeTimeController.php">

    <h2>Set Your Free Time</h2>
    <br>
    <label for="forSunday[]">Sunday:</label>
        <input type="checkbox" name="forSunday[]" value="08:00-09:30">08:00-09:30
        <input type="checkbox" name="forSunday[]" value="09:30-11:00">09:30-11:00
        <input type="checkbox" name="forSunday[]" value="11:00-12:30">11:00-12:30
        <input type="checkbox" name="forSunday[]" value="12:30-02:00"> 12:30-02:00
        <input type="checkbox" name="forSunday[]" value="02:00-03:30">02:00-03:30
        <input type="checkbox" name="forSunday[]" value="03:30-05:00">03:30-05:00<br>

     <label for="forMonday[]">Monday:</label>
        <input type="checkbox" name="forMonday[]" value="08:00-09:30">08:00-09:30
        <input type="checkbox" name="forMonday[]" value="09:30-11:00">09:30-11:00
        <input type="checkbox" name="forMonday[]" value="11:00-12:30">11:00-12:30
        <input type="checkbox" name="forMonday[]" value="12:30-02:00"> 12:30-02:00
        <input type="checkbox" name="forMonday[]" value="02:00-03:30">02:00-03:30
        <input type="checkbox" name="forMonday[]" value="03:30-05:00">03:30-05:00<br>
 <label for="forTuesday[]">Tuesday:</label>
        <input type="checkbox" name="forTuesday[]" value="08:00-09:30">08:00-09:30
        <input type="checkbox" name="forTuesday[]" value="09:30-11:00">09:30-11:00
        <input type="checkbox" name="forTuesday[]" value="11:00-12:30">11:00-12:30
        <input type="checkbox" name="forTuesday[]" value="12:30-02:00"> 12:30-02:00
        <input type="checkbox" name="forTuesday[]" value="02:00-03:30">02:00-03:30
        <input type="checkbox" name="forTuesday[]" value="03:30-05:00">03:30-05:00<br>
 <label for="forWednesday[]">Wednesday:</label>
        <input type="checkbox" name="forWednesday[]" value="08:00-09:30">08:00-09:30
        <input type="checkbox" name="forWednesday[]" value="09:30-11:00">09:30-11:00
        <input type="checkbox" name="forWednesday[]" value="11:00-12:30">11:00-12:30
        <input type="checkbox" name="forWednesday[]" value="12:30-02:00"> 12:30-02:00
        <input type="checkbox" name="forWednesday[]" value="02:00-03:30">02:00-03:30
        <input type="checkbox" name="forWednesday[]" value="03:30-05:00">03:30-05:00<br>
 <label for="forThursday[]">Thursday:</label>
        <input type="checkbox" name="forThursday[]" value="08:00-09:30">08:00-09:30
        <input type="checkbox" name="forThursday[]" value="09:30-11:00">09:30-11:00
        <input type="checkbox" name="forThursday[]" value="11:00-12:30">11:00-12:30
        <input type="checkbox" name="forThursday[]" value="12:30-02:00"> 12:30-02:00
        <input type="checkbox" name="forThursday[]" value="02:00-03:30">02:00-03:30
        <input type="checkbox" name="forThursday[]" value="03:30-05:00">03:30-05:00<br>
    
    <span class="error"><?php if(isset($_GET["dayErr"])){echo $_GET["dayErr"];}?></span>

<button type="submit" name="saveFreeTime" value="saveFreeTime">Save Free Time</button>
<button type="reset" name="reset" value="reset">Reset</button>
</form>

</body>
</html>
