<?php
session_start();
require_once '../config/db_con.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->execute(['email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Në versionin real, këtu do krijonim një token dhe dërgonim email
        echo "A link will be shortly send to your email: $email<br>";
        echo "<a href='../index.php'>Back to log in page</a>";
    } else {
        echo "This email could not be found.<br>";
        echo "<a href='forgot_password.php'>Try again</a>";
    }
    exit(); // PËR TË MOS SHFAQUR FORMËN MË POSHTË
}
?>



<!DOCTYPE html>
<html lang="sq">
<head>

    <meta charset="UTF-8">
    <title>Reset password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/theme-toggle.js"></script>
</head>
<body>
    <div class="container">
        <h2>Password recovery</h2>
        <form action="forgot_password.php" method="POST">
            <input type="email" id="email" name="email" placeholder="Your email" required>
            <input type="submit" value="Send recovery link">
        </form>

        <p><a href="../index.php">Back</a></p>
    </div>
    <button onclick="toggleTheme()" style="position: fixed; top: 10px; right: 10px; padding: 8px 12px;">
    Ndrysho Temën
</button>
</body>
</html>