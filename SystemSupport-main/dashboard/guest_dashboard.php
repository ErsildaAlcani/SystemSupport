<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Guest Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/theme-toggle.js"></script>
</head>
<body class="dashboard-body">
    <div class="dashboard-container">
        <h2 class="dashboard-title">Mirë se vini!</h2>
        <p style="text-align: center;">Jeni aktualisht duke përdorur sistemin si mysafir.<br>Kyçuni ose regjistrohuni për më shumë akses.</p>
        <a href="../index.php" class="logout-link">Kthehu tek faqja kryesore</a>
    </div>
    <button onclick="toggleTheme()" style="position: fixed; top: 10px; right: 10px; padding: 8px 12px;">
    Ndrysho Temën
</button>
</body>
</html>