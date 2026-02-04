<?php
session_start();
$s_id="";
$t_id="";

if($_SESSION['role']==2){
    $s_id=$_SESSION['loginId']??"";
}else if($_SESSION['role']==3){
    $t_id=$_SESSION['loginId']??"";
}

if($t_id===""){ $t_id=null; }
if($s_id===""){ $s_id=null; }

require_once("../Model/setFreeTimeModel.php");

// ✅ Get saved free time to pre-check
$freeMap = getFreeTimeMap($s_id, $t_id);

// helper
function checkedSlot($freeMap, $day, $slot){
    return (isset($freeMap[$day]) && in_array($slot, $freeMap[$day])) ? "checked" : "";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Add Free Time | AIUB CampusConnect</title>
<link rel="stylesheet" href="student/signup.css">

<style>
    .ftWrap{ max-width: 1000px; margin: 20px auto; background:#fff; padding:20px; border-radius:10px; }
    .ftTop{ display:flex; justify-content:space-between; align-items:center; gap:10px; }
    .dayBox{ border:1px solid #ddd; border-radius:10px; padding:12px; margin:12px 0; }
    .dayTitle{ font-weight:700; margin-bottom:10px; }
    .slotRow{ display:flex; flex-wrap:wrap; gap:12px 16px; }
    .slotRow label{ display:flex; align-items:center; gap:6px; }
    .btnRow{ display:flex; gap:10px; margin-top:15px; }
    .backBtn{ text-decoration:none; padding:10px 14px; border:1px solid #333; border-radius:8px; display:inline-block; }
</style>

</head>
<body>

<div class="ftWrap">
    <div class="ftTop">
        <h2 style="margin:0;">Set Your Free Time</h2>

        <?php if($_SESSION['role']==2){ ?>
            <a class="backBtn" href="student/studentHome.php">← Back to Home</a>
        <?php } else { ?>
            <a class="backBtn" href="teacher/teacherHome.php">← Back to Home</a>
        <?php } ?>
    </div>

    <form method="post" action="../Controller/setFreeTimeController.php">

        <div class="dayBox">
            <div class="dayTitle">Sunday</div>
            <div class="slotRow">
                <label><input type="checkbox" name="forSunday[]" value="08:00-09:30" <?php echo checkedSlot($freeMap,"Sunday","08:00-09:30"); ?>>08:00-09:30</label>
                <label><input type="checkbox" name="forSunday[]" value="09:30-11:00" <?php echo checkedSlot($freeMap,"Sunday","09:30-11:00"); ?>>09:30-11:00</label>
                <label><input type="checkbox" name="forSunday[]" value="11:00-12:30" <?php echo checkedSlot($freeMap,"Sunday","11:00-12:30"); ?>>11:00-12:30</label>
                <label><input type="checkbox" name="forSunday[]" value="12:30-02:00" <?php echo checkedSlot($freeMap,"Sunday","12:30-02:00"); ?>>12:30-02:00</label>
                <label><input type="checkbox" name="forSunday[]" value="02:00-03:30" <?php echo checkedSlot($freeMap,"Sunday","02:00-03:30"); ?>>02:00-03:30</label>
                <label><input type="checkbox" name="forSunday[]" value="03:30-05:00" <?php echo checkedSlot($freeMap,"Sunday","03:30-05:00"); ?>>03:30-05:00</label>
            </div>
        </div>

        <div class="dayBox">
            <div class="dayTitle">Monday</div>
            <div class="slotRow">
                <label><input type="checkbox" name="forMonday[]" value="08:00-09:30" <?php echo checkedSlot($freeMap,"Monday","08:00-09:30"); ?>>08:00-09:30</label>
                <label><input type="checkbox" name="forMonday[]" value="09:30-11:00" <?php echo checkedSlot($freeMap,"Monday","09:30-11:00"); ?>>09:30-11:00</label>
                <label><input type="checkbox" name="forMonday[]" value="11:00-12:30" <?php echo checkedSlot($freeMap,"Monday","11:00-12:30"); ?>>11:00-12:30</label>
                <label><input type="checkbox" name="forMonday[]" value="12:30-02:00" <?php echo checkedSlot($freeMap,"Monday","12:30-02:00"); ?>>12:30-02:00</label>
                <label><input type="checkbox" name="forMonday[]" value="02:00-03:30" <?php echo checkedSlot($freeMap,"Monday","02:00-03:30"); ?>>02:00-03:30</label>
                <label><input type="checkbox" name="forMonday[]" value="03:30-05:00" <?php echo checkedSlot($freeMap,"Monday","03:30-05:00"); ?>>03:30-05:00</label>
            </div>
        </div>

        <div class="dayBox">
            <div class="dayTitle">Tuesday</div>
            <div class="slotRow">
                <label><input type="checkbox" name="forTuesday[]" value="08:00-09:30" <?php echo checkedSlot($freeMap,"Tuesday","08:00-09:30"); ?>>08:00-09:30</label>
                <label><input type="checkbox" name="forTuesday[]" value="09:30-11:00" <?php echo checkedSlot($freeMap,"Tuesday","09:30-11:00"); ?>>09:30-11:00</label>
                <label><input type="checkbox" name="forTuesday[]" value="11:00-12:30" <?php echo checkedSlot($freeMap,"Tuesday","11:00-12:30"); ?>>11:00-12:30</label>
                <label><input type="checkbox" name="forTuesday[]" value="12:30-02:00" <?php echo checkedSlot($freeMap,"Tuesday","12:30-02:00"); ?>>12:30-02:00</label>
                <label><input type="checkbox" name="forTuesday[]" value="02:00-03:30" <?php echo checkedSlot($freeMap,"Tuesday","02:00-03:30"); ?>>02:00-03:30</label>
                <label><input type="checkbox" name="forTuesday[]" value="03:30-05:00" <?php echo checkedSlot($freeMap,"Tuesday","03:30-05:00"); ?>>03:30-05:00</label>
            </div>
        </div>

        <div class="dayBox">
            <div class="dayTitle">Wednesday</div>
            <div class="slotRow">
                <label><input type="checkbox" name="forWednesday[]" value="08:00-09:30" <?php echo checkedSlot($freeMap,"Wednesday","08:00-09:30"); ?>>08:00-09:30</label>
                <label><input type="checkbox" name="forWednesday[]" value="09:30-11:00" <?php echo checkedSlot($freeMap,"Wednesday","09:30-11:00"); ?>>09:30-11:00</label>
                <label><input type="checkbox" name="forWednesday[]" value="11:00-12:30" <?php echo checkedSlot($freeMap,"Wednesday","11:00-12:30"); ?>>11:00-12:30</label>
                <label><input type="checkbox" name="forWednesday[]" value="12:30-02:00" <?php echo checkedSlot($freeMap,"Wednesday","12:30-02:00"); ?>>12:30-02:00</label>
                <label><input type="checkbox" name="forWednesday[]" value="02:00-03:30" <?php echo checkedSlot($freeMap,"Wednesday","02:00-03:30"); ?>>02:00-03:30</label>
                <label><input type="checkbox" name="forWednesday[]" value="03:30-05:00" <?php echo checkedSlot($freeMap,"Wednesday","03:30-05:00"); ?>>03:30-05:00</label>
            </div>
        </div>

        <div class="dayBox">
            <div class="dayTitle">Thursday</div>
            <div class="slotRow">
                <label><input type="checkbox" name="forThursday[]" value="08:00-09:30" <?php echo checkedSlot($freeMap,"Thursday","08:00-09:30"); ?>>08:00-09:30</label>
                <label><input type="checkbox" name="forThursday[]" value="09:30-11:00" <?php echo checkedSlot($freeMap,"Thursday","09:30-11:00"); ?>>09:30-11:00</label>
                <label><input type="checkbox" name="forThursday[]" value="11:00-12:30" <?php echo checkedSlot($freeMap,"Thursday","11:00-12:30"); ?>>11:00-12:30</label>
                <label><input type="checkbox" name="forThursday[]" value="12:30-02:00" <?php echo checkedSlot($freeMap,"Thursday","12:30-02:00"); ?>>12:30-02:00</label>
                <label><input type="checkbox" name="forThursday[]" value="02:00-03:30" <?php echo checkedSlot($freeMap,"Thursday","02:00-03:30"); ?>>02:00-03:30</label>
                <label><input type="checkbox" name="forThursday[]" value="03:30-05:00" <?php echo checkedSlot($freeMap,"Thursday","03:30-05:00"); ?>>03:30-05:00</label>
            </div>
        </div>

        <span class="error"><?php if(isset($_GET["dayErr"])){echo $_GET["dayErr"];}?></span>

        <div class="btnRow">
            <button type="submit" name="saveFreeTime" value="saveFreeTime">Save Free Time</button>
            <button type="reset">Reset</button>
        </div>

    </form>
</div>

</body>
</html>
