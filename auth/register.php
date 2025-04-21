<?php
require_once '../config/db_con.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords are different!";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT role_id FROM Roles WHERE role_name = 'client'");
    $stmt->execute();
    $role = $stmt->fetch();

    if ($role) {
        $role_id = $role['role_id'];
         // Fushat që do të dërgohen në databazë
        $supervisor_id = NULL; // Asnjë supervisor në këtë moment
        $max_tickets = NULL; // NULL për klientin
        $current_tickets = NULL; // NULL për klientin
        $is_active = NULL; // NULL për klientin
        $hiring_date = NULL; // NULL për klientin
        $contract_type = NULL; // NULL për klientin

  // Përgatitja e pyetjes INSERT
  $stmt = $conn->prepare("INSERT INTO Users
            (user_id, supervisor_id, role_id, username, password_hash, email, first_name, last_name, phone_number, created_at, modified_at, is_active, max_tickets, current_tickets, hiring_date, contract_type) 
            VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?)");

            $stmt->execute([$supervisor_id, $role_id, $username, $password_hash, $email, $first_name, $last_name, $phone_number, $is_active, $max_tickets, $current_tickets, $hiring_date, $contract_type]);

        echo "Signed up successfully ! <a href='../index.php?role=client'>Log in now!</a>";
    } else {
        echo "Invalid role error!";
    }
}
?>

<h2>Regjistrohu si Klient</h2>
<form method="POST">
    <input type="text" name="first_name" placeholder="Name" required><br>
    <input type="text" name="last_name" placeholder="Last name" required><br>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="text" name="phone_number" placeholder="Phone Number" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirm_password" placeholder="Repeat password" required><br>
    <button type="submit">Sign up</button>
</form>
