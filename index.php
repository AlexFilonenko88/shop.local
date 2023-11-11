<?php

ini_set('display_errors', true);

require_once __DIR__ . "/vendor/autoload.php";

use app\models\Products;

$products = Products::all(); // все товары в отдельную пременную

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>
<body>
<header>
    <h1>Магазин книг</h1>
</header>

<section>
    <div class="container">
        <div class="products">
            <?php foreach ($products as $product) { ?>
                <div class="product">
                    <div class="image">
                        <img src="<? $product['image'] ?>" alt="">
                    </div>
                    <div class="title"><?= $product['name'] ?></div>
                    <div class="price"><?= $product['price'] ?></div>
                    <for class="product-form">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="submit" value="Купить" class="btn">
                    </for>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<footer>
    Все права защищены
</footer>

<script src="js/script.js"></script>
</body>
</html>
