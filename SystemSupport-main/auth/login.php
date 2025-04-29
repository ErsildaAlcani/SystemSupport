<?php
session_start();
require_once '../config/db_con.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Merr të dhënat e përdoruesit bashkë me emrin e rolit
    $query = "
        SELECT u.*, r.role_name 
        FROM users u 
        JOIN roles r ON u.role_id = r.role_id 
        WHERE u.username = :username
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kontrollo nëse ekziston përdoruesi dhe nëse fjalëkalimi është i saktë
    if ($user && isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role_name'];

        // Ridrejto sipas rolit
        switch ($user['role_name']) {
            case 'client':
                header("Location: ../dashboard/client_dashboard.php");
                break;
            case 'agent':
                header("Location: ../dashboard/agent_dashboard.php");
                break;
            case 'agent_supervisor':
                header("Location: ../dashboard/supervisor_dashboard.php");
                break;
            case 'admin':
                header("Location: ../dashboard/admin_dashboard.php");
                break;
            default:
                echo "Roli nuk njihet.";
                break;
        }
        exit;
    } else {
        echo "Kredencialet janë të pasakta.";
    }
} else {
    echo "Kërkesë e pavlefshme.";
}
?>



<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <!-- Lidhja me stilin -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/theme-toggle.js"></script>
</head>
<body>
    <div class="container">
        <h2>Log In</h2>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Log In">
        </form>
        <p><a href="forgot_password.php">Forgot password?</a></p>
        <p><a href="register.php">Don't have an account? Sign up</a></p>
        <p><a href="../index.php">Back to home</a></p>
    </div>
    <button onclick="toggleTheme()" style="position: fixed; top: 10px; right: 10px; padding: 8px 12px;">
    Ndrysho Temën
</button>
</body>
</html>
