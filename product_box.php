<div class="box">
    <img src="images/<?php echo $product['image']; ?>" alt="">
    <h3><?php echo $product['name']; ?> <span><?php echo $product['price']; ?> zł</span></h3>
    <p><?php echo $product['description']; ?></p>
    <div>
        <a href="product.php?name='<?php echo $product['name']; ?>'">Kup</a>
        <button>Dodaj do koszyka</button>
    </div>
</div>