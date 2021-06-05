<?php
    session_start();
    
    include_once "../../includes/basicFunctions.inc.php";
    include_once "../../includes/user/userFunctions.inc.php";
    
    if (!isLoggedIn())
        redirect('/user/login');
    
    $shopping_items = [];

    include_once "../../includes/connection.inc.php";
    $query = $con->prepare(file_get_contents("../../sql/customer/shopping-list/shopping-list-article.customer.select.sql"));
    $query->bind_param('i', $_SESSION['user']['id']);
    $query->execute();
    $res = $query->get_result();

    while ($row = $res->fetch_assoc()) {
        $shopping_items[] = [
                'id' => $row['shoppingcartarticle_id'],
                'customer_id' => $row['customer_id'],
                'amount' => $row['amount'],
                'article_id' => $row['article_id'],
                'image_path' => $row['image_path'],
                'name' => $row['article_name'],
                'price' => $row['price'],
                'price_total' => $row['price_total'],
        ];
    }
    $query->close();
    $con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
    <?php include "../../resources/head.html" ?>
    <link rel="stylesheet" href="/css/user/shopping-list/style.css">
</head>

<body>
    <?php include "../../resources/header.php" ?>
    <main>
        <h1><?= _['title'] ?></h1>
        <div class="divide">
            <?php if (!count($shopping_items)) : ?>
                <section>
                    <p>Er zitten geen artikelen in uw winkelmand</p>
                </section>
            <?php else: ?>
                <div>
                    <table id="shoppinglist-items">
                        <thead>
                            <tr>
                                <th class="article-img-name" colspan="2"><?= _['article'] ?></th>
                                <th class="price-unit"><?= _['unit_cost'] ?></th>
                                <th class="price-total"><?= _['total_price'] ?></th>
                                <th class="amount"><?= _['amount'] ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($shopping_items as $item): ?>
                                <tr>
                                    <td class="article-img center">
                                        <a href="/article/<?= $item['article_id'] ?>" class="bg-image"
                                           style="background-image: url('/images/articles/<?= $item['image_path'] ?>')"></a>
                                    </td>
                                    <td class="name"><span><?= $item['name'] ?></span></td>
                                    <td class="price-unit center">&euro;&nbsp;<?= $item['price'] ?></td>
                                    <td class="price-total center">&euro;&nbsp;<?= $item['price_total'] ?></td>
                                    <td class="amount">
                                        <form action="/user/shopping-list/update" method="get">
                                            <label>
                                                <?= _['quantity'] ?>:
                                                <input type="number" name="amount" min="1" max="99" size="2" value="<?= $item['amount'] ?>">
                                            </label>
                                            <input type="hidden" name="id" value="<?= $item['article_id'] ?>">
                                            <button type="submit" class="btn-blue"><?= _['change'] ?></button>
                                            <a class="btn-blue" href="/user/shopping-list/delete?id=<?= $item['article_id'] ?>"><img src="/images/Icon_trash.svg"
                                                                                                                                     alt="trash can"></a>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <aside>
                    <div class="price-total">
                        <?= _['total'] ?>: <span class="price">&euro;&nbsp;<?= array_sum(array_map('intval', array_column($shopping_items, 'price_total'))) ?></span>
                    </div>
                    <a href="/user/order" class="btn-blue"><?= _['order'] ?></a>
                </aside>
            <?php endif; ?>
        </div>
    </main>
    <?php include "../../resources/footer.php" ?>
</body>

</html>