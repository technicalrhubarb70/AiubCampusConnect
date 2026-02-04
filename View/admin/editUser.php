<?php
session_start();
require_once("../../Model/adminModel.php");

if(!isset($_GET['id'])){
    header("Location: adminHome.php");
    exit();
}

$id = $_GET['id'];
$user = getUserById($id);

if(!$user){
    echo "User not found";
    exit();
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>AiubCampusConnect Admin Dashboard</h1>
<h2>Edit User</h2>

<form method="post" action="../../Controller/admin/updateUser.php">

    <!-- hidden ID (VERY IMPORTANT) -->
    <input type="hidden" name="s_id" value="<?php echo $user['s_id']; ?>">

    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>

        <tr>
            <td>ID</td>
            <td>
                <input type="text" value="<?php echo $user['s_id']; ?>" disabled>
            </td>
        </tr>

        <tr>
            <td>Name</td>
            <td>
                <input type="text" name="s_name" value="<?php echo $user['s_name']; ?>" required>
            </td>
        </tr>

        <tr>
            <td>Gender</td>
            <td>
                <select name="s_gender" required>
                    <option value="male" <?= ($user['s_gender']=="male")?"selected":""; ?>>male</option>
                    <option value="female" <?= ($user['s_gender']=="female")?"selected":""; ?>>female</option>
                    <option value="other" <?= ($user['s_gender']=="other")?"selected":""; ?>>other</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Email</td>
            <td>
                <input type="email" name="s_email" value="<?php echo $user['s_email']; ?>" required>
            </td>
        </tr>

        <tr>
            <td>New Password</td>
            <td>
                <input type="password" name="s_password">
                <small>Leave blank to keep old password</small>
            </td>
        </tr>

        <tr>
            <td>Role</td>
            <td>
                <select name="role">
                    <option value="1" <?= ((int)$user['role']==1)?"selected":""; ?>>Admin</option>
                    <option value="2" <?= ((int)$user['role']==2)?"selected":""; ?>>Student</option>
                    <option value="3" <?= ((int)$user['role']==3)?"selected":""; ?>>Tutor</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Status</td>
            <td>
                <select name="status">
                    <option value="1" <?= ((int)$user['status']==1)?"selected":""; ?>>Active</option>
                    <option value="0" <?= ((int)$user['status']==0)?"selected":""; ?>>Inactive</option>
                </select>
            </td>
        </tr>

        <tr>
            <td>Action</td>
            <td>
                <button type="submit" name="updateUser">Update</button>
                <a href="adminHome.php">Back</a>
            </td>
        </tr>
    </table>
</form>

</body>
</html>
