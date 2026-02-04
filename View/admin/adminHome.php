<?php
session_start();
require_once("../../Model/adminModel.php");

if(isset($_GET['searchId'])){
    $singleUser = getUserById($_GET['searchId']);
    $showSingle = true;
} else {
    $allUsers = getAllUsers();
    $showSingle = false;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Home</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<!-- ✅ FIXED HEADER WITH THEME BUTTON -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h1 style="margin:0;">AiubCampusConnect Admin Dashboard</h1>

    <div style="display:flex; align-items:center; gap:10px;">
        <button type="button" id="themeBtn" class="theme-btn">Dark</button>

        <a href="../../Controller/logout.php"
           style="text-decoration:none; padding:6px 12px; background:#222; color:#fff; border-radius:4px;">
           Logout
        </a>
    </div>
</div>


<?php
if(isset($_GET['success']) && $_GET['success'] == 'added'){
    echo "<p style='color:green; font-weight:bold;'>User added successfully.</p>";
}
if(isset($_GET['error']) && $_GET['error'] == 'duplicate_id'){
    echo "<p style='color:red;'>User ID already exists.</p>";
}
if(isset($_GET['error']) && $_GET['error'] == 'duplicate_email'){
    echo "<p style='color:red;'>Email already exists.</p>";
}
if(isset($_GET['error']) && $_GET['error'] == 'failed'){
    echo "<p style='color:red;'>Failed to add user.</p>";
}
?>

<div class="search-wrap">
    <input type="text" id="searchKey" placeholder="Search by ID / Name / Email" autocomplete="off">
    <div id="dropdown"></div>
</div>

<div style="display:flex; gap:20px; align-items:flex-start;">

<div style="flex:2;">
<h3>All Users</h3>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Gender</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<tbody id="userBody">
<?php

if(isset($showSingle) && $showSingle){

    if($singleUser){

        $id = $singleUser['s_id'];
        $status = (int)$singleUser['status'];

        $statusText  = ($status === 1) ? "Active" : "Inactive";
        $statusClass = ($status === 1) ? "status-active" : "status-inactive";
        $toggleText  = ($status === 1) ? "Turn Off" : "Turn On";

        echo "<tr id='row_$id'>
                <td>{$singleUser['s_id']}</td>
                <td>{$singleUser['s_name']}</td>
                <td>{$singleUser['s_gender']}</td>
                <td>{$singleUser['s_email']}</td>
                <td>{$singleUser['role']}</td>
                <td id='status_$id' class='$statusClass'>$statusText</td>
                <td>
                    <a href='editUser.php?id=$id'>Edit</a> |
                    <a href='../../Controller/admin/deleteUser.php?id=$id'
                       onclick=\"return confirm('Delete this user?')\">Delete</a> |
                    <button class='btn-toggle'
                            id='btn_$id'
                            onclick=\"toggleUserStatus('$id',$status)\">
                        $toggleText
                    </button>
                </td>
              </tr>";

    } else {
        echo "<tr><td colspan='7'>User not found</td></tr>";
    }

} else {

    if($allUsers && mysqli_num_rows($allUsers) > 0){
        while($u = mysqli_fetch_assoc($allUsers)){

            $id = $u['s_id'];
            $status = (int)$u['status'];

            $statusText  = ($status === 1) ? "Active" : "Inactive";
            $statusClass = ($status === 1) ? "status-active" : "status-inactive";
            $toggleText  = ($status === 1) ? "Turn Off" : "Turn On";

            echo "<tr id='row_$id'>
                    <td>{$u['s_id']}</td>
                    <td>{$u['s_name']}</td>
                    <td>{$u['s_gender']}</td>
                    <td>{$u['s_email']}</td>
                    <td>{$u['role']}</td>
                    <td id='status_$id' class='$statusClass'>$statusText</td>
                    <td>
                        <a href='editUser.php?id=$id'>Edit</a> |
                        <a href='../../Controller/admin/deleteUser.php?id=$id'
                           onclick=\"return confirm('Delete this user?')\">Delete</a> |
                        <button class='btn-toggle'
                                id='btn_$id'
                                onclick=\"toggleUserStatus('$id',$status)\">
                            $toggleText
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No users found</td></tr>";
    }

}
?>
</tbody>
</table>
</div>

<div style="flex:1;">
<h3>Add User</h3>

<form method="post" action="../../Controller/admin/addUser.php">
<table>
<tr><td>ID</td><td><input type="text" name="id" required></td></tr>
<tr><td>Name</td><td><input type="text" name="name" required></td></tr>
<tr><td>Gender</td>
<td>
<select name="gender">
<option value="male">male</option>
<option value="female">female</option>
<option value="other">other</option>
</select>
</td></tr>
<tr><td>Email</td><td><input type="email" name="email" required></td></tr>
<tr><td>Password</td><td><input type="password" name="password" required></td></tr>
<tr><td>Role</td>
<td>
<select name="role">
<option value="1">Admin</option>
<option value="2">Student</option>
<option value="3">Tutor</option>
</select>
</td></tr>
<tr><td>Status</td>
<td>
<select name="status">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
</td></tr>
<tr><td colspan="2"><button type="submit" name="add">Add User</button></td></tr>
</table>
</form>
</div>

</div>

<script src="/AiubCampusConnect/View/admin/admin.js" defer></script>

</body>
</html>
