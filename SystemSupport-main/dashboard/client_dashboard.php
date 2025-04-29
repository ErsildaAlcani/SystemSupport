<?php
session_start();
require_once '../config/db_con.php';

// Kontrollo nëse përdoruesi është i kyçur dhe është klient
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Merr të gjitha të dhënat nga tabela users
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user):
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/theme-toggle.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <h2 class="dashboard-title">Profili i klientit</h2>
        <ul class="dashboard-list">
            <li><strong>Emri:</strong> <?= htmlspecialchars($user['first_name']) ?></li>
            <li><strong>Mbiemri:</strong> <?= htmlspecialchars($user['last_name']) ?></li>
            <li><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></li>
            <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
            <li><strong>Telefoni:</strong> <?= htmlspecialchars($user['phone_number']) ?></li>
            <li><strong>Data e krijimit:</strong> <?= $user['created_at'] ?></li>
            <li><strong>Aktiv:</strong> <?= is_null($user['is_active']) ? 'Jo i përcaktuar' : ($user['is_active'] ? 'Po' : 'Jo') ?></li>
        </ul>
        <a class="logout-button" href="../index.php">Dil</a>
    </div>
    <button onclick="toggleTheme()" style="position: fixed; top: 10px; right: 10px; padding: 8px 12px;">
    Ndrysho Temën
</button>
</body>
</html>
<?php
else:
    echo "Nuk u gjetën të dhënat e përdoruesit.";
endif;
?>