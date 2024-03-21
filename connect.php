<?php
$dsn = "mysql:host=localhost;dbname=sklep;";
$root = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $root, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
?>
