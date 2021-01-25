<?php
    $product = array(
            "name" => "DC-FZ1000 II",
            "brand" => array("name" => "LUMIX", "src" => "/images/brands/panasonic.svg"),
            "img" => array(
                    "/images/products/01_FZ1000M2_thumbnail.png",
                    "/images/products/02_FZ1000M2.png",
                    "/images/products/03_FZ1000M2.png",
                    "/images/products/04_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/02_FZ1000M2.png",
                    "/images/products/03_FZ1000M2.png",
                    "/images/products/04_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/05_FZ1000M2.png",
                    "/images/products/06_FZ1000M2.png"
            ),
            "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,  quis ...",
            "stars" => 3,
            "amountOfReviews" => 1,
            "price" => 1599.99,
            "link" => "/article/1/DC-FZ1000-II"
    );
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GigaCam <?= $product["name"] ?> &#9866; <?= $product["brand"]["name"] ?></title>
    <link rel="stylesheet" href="/css/article.css">
    <?php include "../resources/head.html" ?>
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>

    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>