<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in page</title>
    <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }

    .container {
        width: 100%;
        height: 100%;
        background-color: #fff;
        padding: 40px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    h2 {
        text-align: center;
        font-size: 36px;
        margin-bottom: 40px;
    }

    form {
        width: 100%;
        max-width: 800px; /* optional: limit a little bit for readability */
        margin-bottom: 30px;
    }

    label {
        font-size: 18px;
        display: block;
        margin-bottom: 8px;
        text-align: left;
    }

    input[type="text"], input[type="password"], input[type="submit"] {
        width: 100%;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        border: none;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .link {
        text-decoration: none;
        color: #007bff;
        display: inline-block;
        margin-bottom: 20px;
        font-size: 16px;
    }

    .link:hover {
        text-decoration: underline;
    }

    label small {
        display: block;
        margin-top: 10px;
        text-align: center;
        font-size: 14px;
    }

    /* Responsive: for smaller devices */
    @media (max-width: 768px) {
        h2 {
            font-size: 28px;
        }
        form {
            max-width: 90%;
        }
    }
    </style>
</head>
<body>

    <div class="container">
        <h2>LOG IN</h2>

        <form action="auth/login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <a href="auth/forgot_password.php" class="link">Forgot password?</a>

            <input type="submit" value="Enter">
        </form>

        <form action="guest.php" method="POST">
            <input type="submit" value="Continue as guest">
        </form>

        <form action="auth/register.php" method="GET">
            <label>Are you a client without an account yet?</label>
            <input type="submit" value="Sign up now!">
        </form>
    </div>

</body>
</html>

