<?php
require_once(__DIR__ . "/dbconnect.php");

function insertskill($skill_id, $skill_name, $s_id)
{
    $conn = dbconnect();

    $skill_id   = mysqli_real_escape_string($conn, $skill_id);
    $skill_name = mysqli_real_escape_string($conn, $skill_name);
    $s_id       = mysqli_real_escape_string($conn, $s_id);

    $query = "insert into skills (skill_id, skill_name, s_id)
              values ('$skill_id', '$skill_name', '$s_id')";

    return mysqli_query($conn, $query);
}


function getskillsbystudentid($s_id)
{
    $conn = dbconnect();
    $s_id = mysqli_real_escape_string($conn, $s_id);

    $query = "select * from skills where s_id='$s_id' order by skill_name asc";
    $data  = mysqli_query($conn, $query);

    $skills = [];
    if ($data && mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_assoc($data)) {
            $skills[] = $row;
        }
    }

    return $skills;
}


function updateskill($skill_id, $newskillname)
{
    $conn = dbconnect();

    $skill_id     = mysqli_real_escape_string($conn, $skill_id);
    $newskillname = mysqli_real_escape_string($conn, $newskillname);

    $query = "update skills
              set skill_name='$newskillname'
              where skill_id='$skill_id'";

    return mysqli_query($conn, $query);
}

function deleteskill($skill_id)
{
    $conn = dbconnect();
    $skill_id = mysqli_real_escape_string($conn, $skill_id);

    $query = "delete from skills where skill_id='$skill_id'";
    return mysqli_query($conn, $query);
}
function deleteskillByStudentId($s_id)
{
    $conn = dbconnect();
    $query = "delete from skills where s_id='$s_id'";
    return mysqli_query($conn, $query);
}

function getskillnamesbystudentid($s_id)
{
    $conn = dbconnect();
    $s_id = mysqli_real_escape_string($conn, $s_id);

    $query = "select distinct skill_name from skills where s_id='$s_id'";
    $data  = mysqli_query($conn, $query);

    $names = [];
    if ($data && mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_assoc($data)) {
            $names[] = $row['skill_name'];
        }
    }

    return $names;
}


function getmatchedstudentsbyskills($s_id)
{
    $conn = dbconnect();
    $s_id = mysqli_real_escape_string($conn, $s_id);

    $myskills = getskillnamesbystudentid($s_id);
    if (empty($myskills)) {
        return [];
    }

    $escaped = [];
    foreach ($myskills as $sk) {
        $escaped[] = "'" . mysqli_real_escape_string($conn, $sk) . "'";
    }
    $in = implode(",", $escaped);

    $query = "
        select st.s_id, st.s_name,
               group_concat(distinct sk.skill_name order by sk.skill_name separator ', ') as common_skills
        from skills sk
        join student st on st.s_id = sk.s_id
        where sk.skill_name in ($in)
          and sk.s_id <> '$s_id'
        group by st.s_id, st.s_name
        order by st.s_name asc
    ";

    $data = mysqli_query($conn, $query);

    $results = [];
    if ($data && mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_assoc($data)) {
            $results[] = $row;
        }
    }

    return $results;
}
?>
