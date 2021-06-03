<?php
    session_start();
    include_once "../includes/validateFunctions.inc.php";
    include_once "../includes/basicFunctions.inc.php";
    include_once "../includes/user/userFunctions.inc.php";

    $article_id = var_validate($_GET["id"]);
    $article_name = $_GET["name"] ?? '';
    
    if (empty($article_id))
        redirect('/articles');


    include_once "../includes/connection.inc.php";

    $product = array();
    $like = false;

    $query = $con->prepare(file_get_contents("../sql/article/article.select.sql"));
    $query->bind_param('i', $article_id);
    $query->execute();
    $res = $query->get_result();
    
    $row = $res->fetch_assoc();
    $query->close();
    
    if (empty($row))
        redirect('/articles');
    
    $product['name'] = $row['name'];
    $product['id'] = $article_id;
    $product['price'] = $row['price'];
    $product['link'] = "/article/$article_id]/{$product['name']}";
    $product['brand'] = array("name" => $row['brand_name'], "src" => "/images/brands/{$row['logo']}");
    $product['description'] = $row['description' . language()];

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
                    'name' => $row["category_name_" . language()],
                    'specs' => []];
        }
        $product['category'][array_key_last($product['category'])]['specs'][] = array(
                'value' => $row['value'],
                'key' => $row['specification_name_' . language()]);
    }
    $query->close();

    if (isLoggedIn()) {
        $query = $con->prepare("INSERT INTO visited(customer_id, article_id) VALUES (?, ?)");
        $user_id = $_SESSION['user']['id'];
        $query->bind_param('ii', $user_id, $article_id);
        $query->execute();
        $query->close();

        $query = $con->prepare("SELECT COUNT(*) AS amount FROM `like` WHERE article_id = ? AND customers_id = ? LIMIT 1");
        $query->bind_param('ii', $article_id, $user_id);
        $query->execute();
        $res = $query->get_result();
        $row = $res->fetch_assoc();
        $like = intval($row['amount']) > 0;
        $query->close();
    }

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
                <div id="big-image" class="bg-image" style='background-image: url("<?= $product['img'][0] ?>")'></div>
                <div class="images-thumbnails">
                    <ul>
                        <?php
                            foreach ($product["img"] as $i => $src) {
                                if ($i == 0) {
                                    /** @noinspection CssUnknownTarget */
                                    echo "<li  class='active bg-image' style='background-image: url(\"$src\")'></li>";
                                } else {
                                    /** @noinspection CssUnknownTarget */
                                    echo "<li class=\"bg-image\" style='background-image: url(\"$src\")'></li>";
                                }
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
                        <a class="btn-blue like" href="/user/like?id=<?= $product['id'] ?>">
                            <img id="btn-notliked" <?= !$like ? 'class="active"' : "" ?> src="/images/Icon_love_outline.svg" alt="like">
                            <img id="btn-liked" <?= $like ? 'class="active"' : "" ?>src="/images/Icon_love_solid.svg" alt="like">
                        </a>
                    </div>
                </div>
                <div class="add-to-shopping-list">
                    <form action="/user/shopping-list/add">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <label>
                            Hoeveelheid:
                            <input type="number" name="amount" min="1" max="99" size="2" value="1">
                        </label>
                        <button type="submit" class="btn-blue">
                            <img src="/images/Icon_basket-white.svg" alt="like">
                        </button>
                    </form>
                </div>
                <div class="description">
                    <?= $product['description'] ?>
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
                                    <td><?= yes_no_to_unicode($spec['value']) ?></td>
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