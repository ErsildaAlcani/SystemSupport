<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Faqja Kryesore</title>
</head>
<body>

    <h2>LOG IN</h2>
    <form action="auth/login.php" method="POST">
        <label for="username"> Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <p><a href="auth/forgot_password.php">Keni harruar fjalëkalimin?</a></p>

        <input type="submit" value="Enter">
    </form>

    <br>

    <form action="guest.php" method="POST">
        <input type="submit" value="Continue as guest">
    </form>

    <br>

    <form action="auth/register.php" method="GET">
        <label> Are you a client without an account yet? </label><br>
        <input type="submit" value="Sign up now!">
    </form>

</body>
</html>
