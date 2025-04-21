<?php
$host = 'localhost';
$dbname = 'support_system'; 
$username = 'root';           // Në MAMP, root është default
$password = 'root';           // Edhe fjalëkalimi është 'root' në MAMP

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Aktivo error reporting
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lidhja me databazën dështoi: " . $e->getMessage());
}
?>
