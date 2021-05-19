<?php
    session_start();
    include "../includes/connection.inc.php";
    include "../includes/validateFunctions.inc.php";
    include "../includes/basicFunctions.inc.php";

    $article_id = var_validate($_GET["id"]);
    $article_name = $_GET["name"] ?? '';

    if (empty($article_id))
        redirect('/articles');

    $product = array();


    $query = $con->prepare(file_get_contents("../sql/article/article.select.sql"));
    $query->bind_param('i', $article_id);
    $query->execute();
    $res = $query->get_result();

    $row = $res->fetch_assoc();
    $query->close();

    if (empty($row))
        redirect('/articles');

    $product['name'] = $row['name'];
    $product['price'] = $row['price'];
    $product['link'] = "/article/$article_id]/{$product['name']}";
    $product['brand'] = array("name" => $row['brand_name'], "src" => "/images/brands/{$row['logo']}");
    $product['description'] = $row['description' . (!empty($_SESSION['lang']) ?: 'e')];

    $product['stars'] = 5; //TODO product add stars
    $product['amountOfReviews'] = 0; //TODO revieuws

    $product['img'] = array();
    if (!str_compare_GET($article_name, $product['name']))
        redirect("/article/$article_id/" . urlencode($product['name']));

    $query = $con->prepare(file_get_contents("../sql/article/articleImage.select.sql"));
    $query->bind_param('i', $article_id);
    $query->execute();
    $res = $query->get_result();
    while ($row = $res->fetch_assoc()) {
        $product['img'][] = "/images/articles/{$row['path']}";
    }
    $query->close();


    $product['category'] = array();

    $query = $con->prepare(file_get_contents("../sql/article/articleSpecification.select.sql"));
    $query->bind_param('i', $article_id);
    $query->execute();
    $res = $query->get_result();
    $last_cat_id = -1;
    while ($row = $res->fetch_assoc()) {
        if ($last_cat_id != $row['category_id']) {
            $last_cat_id = $row['category_id'];
            $product['category'][] = [
                    'name' => $row["category_name_" . (!empty($_SESSION['lang']) ?: 'e')],
                    'specs' => []];
        }
        $product['category'][array_key_last($product['category'])]['specs'][] = array(
                'value' => $row['value'],
                'key' => $row['specification_name_' . (!empty($_SESSION['lang']) ?: 'e')]);
    }
    $query->close();
    $con->close();
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
                                    echo "<li class='active'> <img src=\"$src\" alt=\"{$product["name"]} $i\"> </li>";
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
                        <button class="btn-blue like">
                            <img id="btn-notliked" class="active" src="/images/Icon_love_outline.svg" alt="like">
                            <img id="btn-liked" src="/images/Icon_love_solid.svg" alt="like">
                        </button>
                    </div>
                </div>
                <div class="add-to-shopping-list">
                    <button class="btn-blue">
                        <img src="/images/Icon_basket-white.svg" alt="like">
                    </button>
                </div>
            </div>
        </div>
        <section class="specifications">
            <?php
                foreach ($product['category'] as $category) { ?>
                    <table>
                        <caption><?= $category['name'] ?></caption>
                        <tbody>
                            <?php foreach ($category['specs'] as $spec) { ?>
                                <tr>
                                    <td><?= $spec['key'] ?></td>
                                    <td><?= $spec['value'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php
                }
            ?>
        </section>
    </main>
    <?php include "../resources/footer.php" ?>
    <script src="/js/article/imagesThumbnails.js"></script>
</body>

</html>