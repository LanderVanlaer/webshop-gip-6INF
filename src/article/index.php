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
        <div class="product-name-logo">
            <div class="left">
                <h1 class="product-name"><?= $product["name"] ?> &#9866; <?= $product["brand"]["name"] ?></h1>
            </div>
            <div class="right">
                <img src="<?= $product["brand"]["src"] ?>" alt="Logo <?= $product["brand"]["name"] ?>">
            </div>
        </div>
        <div class="product-top">
            <div class="images">
                <ul class="big-images">
                    <?php
                        foreach ($product["img"] as $i => $src) {
                            if ($i == 0)
                                echo "<li> <img class='active' src=\"$src\" alt=\"{$product["name"]} $i\"> </li>";
                            else
                                echo "<li> <img src=\"$src\" alt=\"{$product["name"]} $i\"> </li>";
                        }
                    ?>
                </ul>
                <div class="images-thumbnails">
                    <ul>
                        <?php
                            foreach ($product["img"] as $i => $src) {
                                if ($i == 0)
                                    echo "<li> <img class='active' src=\"$src\" alt=\"{$product["name"]} $i\"> </li>";
                                else
                                    echo "<li> <img src=\"$src\" alt=\"{$product["name"]} $i\"> </li>";
                            }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="price-buy">
                <div class="price-like">
                    <div>
                        <h1 class="price">&euro; <?= $product["price"] ?></h1>
                    </div>
                    <div>
                        <button class="like">
                            <img class="active" src="/images/Icon_love_outline.svg" alt="like">
                            <img src="/images/Icon_love_solid.svg" alt="like">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>