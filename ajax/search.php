<?php
require '../connect.php';

$searchText = $_POST['query'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ?");
$stmt->execute(["%$searchText%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($products) > 0) {
    foreach ($products as $product) include "../product_box.php";
} else {
    echo "Nie znaleziono produktu.";
}
?>
