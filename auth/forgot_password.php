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
</head>
<body>

    <h2>Password recovery</h2>
    <form action="forgot_password.php" method="POST">
        <label for="email">Your email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Send recovery link">
    </form>

    <p><a href="../index.php">Turn back</a></p>

</body>
</html>
