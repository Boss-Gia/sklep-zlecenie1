<?php
require 'connect.php';

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="#">Strona główna</a></li>
            <li><a href="#shoppingcart">Koszyk</a></li>
            <li><a href="#contact">Kontakt</a></li>
        </ul>
        <a href="logout.php">Wyloguj się</a>
    </nav>
</header>
<main>
    <div class="search_box">
        <input type="search" class="input" placeholder="search..." id="search">
        <div class="btn" id="searchBtn">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div class="box-container" id="productContainer">
        <?php foreach ($products as $product) include "product_box.php"; ?>
    </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('#searchBtn').click(function(){
            var searchText = $('#search').val();
            $.ajax({
                url: 'ajax/search.php',
                method: 'post',
                data: {query: searchText},
                success: function(response){
                    $('#productContainer').html(response);
                }
            });
        });
    });
</script>
</body>
</html>
