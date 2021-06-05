<?php
    include_once "../includes/Error.php";
    
    use includes\Error as Error;
    
    include_once "../includes/user/userFunctions.inc.php";
    session_start();
    $logged_in = isLoggedIn();

    $historyArticles = [];
    $likedArticles = [];

    if (isLoggedIn()) {
        include_once "../includes/connection.inc.php";
        $query = $con->prepare(file_get_contents("../sql/customer/visited.customer.select.sql"));
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

        $query = $con->prepare(file_get_contents("../sql/customer/like.customer.select.sql"));
        $query->bind_param('i', $user_id);
        $query->execute();

        $res = $query->get_result();
        while ($row = $res->fetch_assoc()) {
            $likedArticles[] = [
                    'id' => $row['article_id'],
                    'name' => $row['article_name'],
                    'path' => $row['img_path']
            ];
        }
        $query->close();

        $orders = [];

        $query = $con->prepare(file_get_contents("../sql/customer/order-orderarticle.customer.select.sql"));
        $query->bind_param('i', $user_id);
        $query->execute();

        $res = $query->get_result();
        while ($row = $res->fetch_assoc()) {
            if (!count($orders) || $orders[array_key_last($orders)]['date'] != $row['date']) {
                $orders[] = [
                        'date' => $row['date'],
                        'articles' => [],
                ];
            }

            $orders[array_key_last($orders)]['articles'][] = [
                    'id' => $row['article_id'],
                    'name' => $row['article_name'],
                    'amount' => $row['amount'],
                    'price' => [
                            'unit' => $row['price_unit'],
                            'total' => $row['price_total'],
                    ],
            ];
        }
        $query->close();

        $con->close();
    }
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
    <?php include "../resources/head.html" ?>
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/user/style.css">
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>
        <h1><?= _['title'] ?></h1>
        <?php if ($logged_in): ?>
            <a class="btn-blue" href="logout"><?= _['sign_out'] ?></a>
            <div class="user-welcome-message">
                <h3><?= _['hello'] ?> <?= $_SESSION['user']['firstname'] ?></h3>
            </div>
            <form action="/user/changePassword/" method="POST">
                <?php if (isset($_GET['error'])) Error::print_admin_message($_GET['error']); ?>
                <fieldset>
                    <legend><?= _['change_password'] ?></legend>
                    <table>
                        <tr>
                            <td><label for="password"><?= _['old_password'] ?></label></td>
                            <td><input type="password" id="password" name="password"></td>
                        </tr>
                        <tr>
                            <td><label for="password_new"><?= _['new_password'] ?></label></td>
                            <td><input type="password" id="password_new" name="password_new"></td>
                        </tr>
                        <tr>
                            <td><label for="password_new_confirm"><?= _['confirm_password'] ?></label></td>
                            <td><input type="password" id="password_new_confirm" name="password_new_confirm"></td>
                        </tr>
                    </table>
                    <button class="btn-blue" type="submit"><?= _['change'] ?></button>
                </fieldset>
            </form>
            <section class="liked">
                <h2><?= _['wishlist'] ?></h2>
                <div class="split">
                    <?php foreach ($likedArticles as $article) : ?>
                        <article>
                            <a href="/article/<?= $article['id'] ?>">
                                <img class="product-name-logo margin-center" src="/images/articles/<?= $article['path'] ?>" alt="">
                                <span class="product-name"><?= $article['name'] ?></span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <section class="history">
                <h2><?= _['history'] ?></h2>
                <div class="split">
                    <?php foreach ($historyArticles as $article) : ?>
                        <article>
                            <a href="/article/<?= $article['id'] ?>">
                                <img class="product-name-logo margin-center" src="/images/articles/<?= $article['path'] ?>" alt="">
                                <span class="product-name"><?= $article['name'] ?></span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <section class="orders">
                <h2><?= _['purchases'] ?></h2>
                <div class="tables split">
                    <?php foreach ($orders as $order) : ?>
                        <div>
                            <table>
                                <thead>
                                    <tr>
                                        <th><?= _['date'] ?>:</th>
                                        <th colspan="4"><?= $order['date'] ?></th>
                                    </tr>
                                    <tr>
                                        <th>ID</th>
                                        <th><?= _['name'] ?></th>
                                        <th><?= _['price'] ?></th>
                                        <th><?= _['amount'] ?></th>
                                        <th><?= _['total'] ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['articles'] as $article) : ?>
                                        <tr>
                                            <td><?= $article['id'] ?></td>
                                            <td><?= $article['name'] ?></td>
                                            <td>&euro;&nbsp;<?= $article['price']['unit'] ?></td>
                                            <td><?= $article['amount'] ?></td>
                                            <td>&euro;&nbsp;<?= $article['price']['total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <a class="btn-blue" href="register">Registreren</a>
            <a class="btn-blue" href="login">Aanmelden</a>
        <?php endif; ?>
    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>