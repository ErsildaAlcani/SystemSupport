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
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <script src="../assets/js/theme-toggle.js"></script>
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <h2>Profili i administratorit</h2>
        <ul class="user-info">
            <li><strong>Emri:</strong> <?= htmlspecialchars($user['first_name']) ?></li>
            <li><strong>Mbiemri:</strong> <?= htmlspecialchars($user['last_name']) ?></li>
            <li><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
            <li><strong>Telefoni:</strong> <?= htmlspecialchars($user['phone_number']) ?></li>
            <li><strong>Data e krijimit:</strong> <?= $user['created_at'] ?></li>
            <li><strong>Statusi aktiv:</strong> <?= is_null($user['is_active']) ? 'Jo i përcaktuar' : ($user['is_active'] ? 'Po' : 'Jo') ?></li>
        </ul>
        <a class="logout-button" href="../logout.php">Dil</a>
    </div>
    <button onclick="toggleTheme()" style="position: fixed; top: 10px; right: 10px; padding: 8px 12px;">
    Ndrysho Temën
</button>
</body>
</html>
<?php
else:
    echo "Nuk u gjetën të dhënat.";
endif;
?>