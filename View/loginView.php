<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MuseumConnect</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <main class="auth-container">
        <section class="auth-card">
            <header class="logo-section">
                <img src="../Resourses/logo.png" alt="MuseumConnect Logo" class="logo">
            </header>
            
            <h1 class="auth-title">Login</h1>
            
<form action="../Controller/authControl.php" method="POST">
                <fieldset class="input-group">
                    <label for="userId" class="input-label">User ID:</label>
                    <input type="text" name="userId" id="userId" class="input-field" placeholder="Input your user ID">
                    <span class="error-message"><?php if(isset($_GET["idErr"])){echo $_GET["idErr"];} ?></span>
                </fieldset>

                <fieldset class="input-group">
                    <label for="pass" class="input-label">Password:</label>
                    <input type="password" name="pass" id="pass" class="input-field">
                    <span class="error-message"><?php if(isset($_GET["passErr"])){echo $_GET["passErr"];} ?></span>
                </fieldset>

                <span class="error-message error-login"><?php if(isset($_GET["loginErr"])){echo $_GET["loginErr"];} ?></span>

                <fieldset class="button-row">
                    <input type="submit" name="submit" value="Login" class="btn btn-primary">
                    <input type="reset" name="reset" value="Reset" class="btn btn-secondary">
                </fieldset>
            </form>
            
            <button class="btn btn-outline" onclick="window.location.href='student/signUpView.php'">Sign Up</button>
        </section>
    </main>
</body>
</html>