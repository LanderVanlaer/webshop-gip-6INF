<?php
    session_start();
    include_once "includes/user/userFunctions.inc.php";

    include_once "includes/connection.inc.php";

    $categories = [];

    $categoryQuery = $con->query("SELECT id, nameD, nameF, nameE FROM category;");
    while ($row = $categoryQuery->fetch_assoc())
        $categories[] = [
                'id' => $row['id'],
                'name' => $row['name' . language(true)],
        ];
    $categoryQuery->close();

    if (isLoggedIn()) {
        $historyArticles = [];

        $query = $con->prepare(file_get_contents("sql/customer/visited.customer.select.sql"));
        $user_id = $_SESSION['user']['id'];
        $query->bind_param('i', $user_id);
        $query->execute();

        $res = $query->get_result();
        while ($row = $res->fetch_assoc()) {
            $historyArticles[] = [
                    'id' => $row['article_id'],
                    'name' => $row['article_name'],
                    'path' => $row['img_path']
            ];
        }
        $query->close();
    }

    $con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GigaCam - Home</title>
    <?php include "resources/head.html" ?>
    <link rel="stylesheet" href="/css/home.css">
</head>

<body>
    <?php include "resources/header.php" ?>
    <main>
        <div class="top">
            <h1>GigaCam</h1>
            <h2><?= _['baseline'] ?></h2>
            <img class="logo" src="/images/logo_basic.svg" alt="Logo GigaCam">
        </div>
        <section class="categories">
            <h2><?= _['category'] ?></h2>
            <nav>
                <ul>
                    <?php foreach ($categories as $category): ?>
                        <li><a href="/articles/<?= $category['id'] ?>" class="btn-blue"><?= $category['name'] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </section>
        <?php if (isLoggedIn()): ?>
            <section class="history">
                <h2><?= _['history'] ?></h2>
                <div>
                    <div class="split" style="width: <?= 25 * count($historyArticles) ?>rem">
                        <?php foreach ($historyArticles as $article) : ?>
                            <article>
                                <a href="/article/<?= $article['id'] ?>">
                                    <img class="product-name-logo margin-center" src="/images/articles/<?= $article['path'] ?>" alt="">
                                    <span class="product-name"><?= $article['name'] ?></span>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php include "resources/footer.php" ?>
</body>

</html>