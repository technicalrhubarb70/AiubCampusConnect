<?php

?>


<!doctype>
<html>
<head>
    <title>SignUp</title>
</head>
<body>
    <form action="../../Controller/signUpValidation.php" method="POST" enctype="multipart/form-data">
        <label for="sName">Name:</label>
        <input type="text"  name="sName" placeholder="enter your full name">
        <span name="sNameErr" ><?php if(isset($_GET["sNameErr"])){echo $_GET["sNameErr"];}?> </span><br><br>

        <label for="sGender">Name:</label>
        <input type="radio"  name="sGender" value="male">Male
        <input type="radio" name="sGender" value="female">Female
        <span name="sGenderErr" ><?php if(isset($_GET["sGenderErr"])){echo $_GET["sGenderErr"];}?></span><br><br>

        <label for="sPassword">Password:</label>
        <input type="password" name="sPassword">
        <span name="sPasswordErr" ><?php if(isset($_GET["sPasswordErr"])){echo $_GET["sPasswordErr"];}?></span><br><br>

        <label for="sEmail">Email:</label>
        <input type="text" name="sEmail" placeholder="enter aiub email">
        <span name="sEmailErr" ><?php if(isset($_GET["sEmailErr"])){echo $_GET["sEmailErr"];}?></span><br><br>

        <label for="sProPic">Upload Profile picture:</label>
        <input type="file"  name="sProPic" placeholder="JPEG,PNG formats allowed">
        <span name="sProPicErr" ><?php if(isset($_GET["sProPicErr"])){echo $_GET["sProPicErr"];}?></span><br><br>

        <input type="submit" name="submit" value="submit">    
        <input type="reset" name="reset" value="reset"><br>
    </form>
</body>
</html>
