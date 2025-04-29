<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Faqja Kryesore</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Stili default -->
    <link rel="stylesheet" href="assets/css/style_dark.css"> <!-- Dark mode style -->
    <script src="assets/js/theme-toggle.js"></script> <!-- JavaScript për temën -->
</head>
<body>

    <div class="container">
        <h2>LOG IN</h2>
        <form action="auth/login.php" method="POST" class="form-box">
            <label for="username"> Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>

            <p><a href="auth/forgot_password.php">Keni harruar fjalëkalimin?</a></p>

            <input type="submit" value="Enter">
        </form>

        <br>

        <form action="dashboard/guest_dashboard.php" method="POST" class="form-box">
            <input type="submit" value="Continue as guest">
        </form>

        <br>

        <form action="auth/register.php" method="GET" class="form-box">
            <label> Are you a client without an account yet? </label><br>
            <input type="submit" value="Sign up now!">
        </form>
    </div>

    <!-- Butoni për ndryshim teme -->
    <button onclick="toggleTheme()" style="position: fixed; top: 10px; right: 10px; padding: 8px 12px;">
        Ndrysho Temën
    </button>

</body>
</html>