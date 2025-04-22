<?php
session_start();
require_once '../config/db_con.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user):
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin profile</h2>
    <ul>
        <li><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']) ?></li>
        <li><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']) ?></li>
        <li><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></li>
        <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
        <li><strong>Phone number:</strong> <?= htmlspecialchars($user['phone_number']) ?></li>
        <li><strong>Creation Date:</strong> <?= $user['created_at'] ?></li>
        <li><strong>Status:</strong> <?= is_null($user['is_active']) ? 'Not Defined' : ($user['is_active'] ? 'Yes' : 'No') ?></li>
    </ul>
    <a href="../logout.php">Dil</a>
</body>
</html>
<?php
else:
    echo "No data was found";
endif;
?>