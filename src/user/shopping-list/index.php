<?php
    session_start();
    
    include "../../includes/basicFunctions.inc.php";
    include "../../includes/user/userFunctions.inc.php";
    
    if (!isLoggedIn())
        redirect('/user/login');
    
    $shopping_items = [];
    
    include "../../includes/connection.inc.php";
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
    <title>Document</title>
    <?php include "../../resources/head.html" ?>
    <link rel="stylesheet" href="/css/user/shopping-list/style.css">
</head>

<body>
    <?php include "../../resources/header.php" ?>
    <main>
        <h1>Winkelmand</h1>
        <div class="divide">
            <?php if (!count($shopping_items)) : ?>
                <section>
                    <p>Er zitten geen artikelen in uw winkelmand</p>
                </section>
            <?php else: ?>
                <section id="shoppinglist-items">
                    <?php foreach ($shopping_items as $item) { ?>
                        <article class="divide">
                            <div class="left">
                                <a href="/article/<?= $item['article_id'] ?>" class="bg-image" style="background-image: url('/images/articles/<?= $item['image_path'] ?>')"></a>
                                <h3><?= $item['name'] ?></h3>
                            </div>
                            <div class="right">
                                <form action="/user/shopping-list/update" method="get">
                                    <label>
                                        Hoeveelheid:
                                        <input type="number" name="amount" min="1" max="99" size="2" value="<?= $item['amount'] ?>">
                                    </label>
                                    <input type="hidden" name="id" value="<?= $item['article_id'] ?>">
                                    <button type="submit" class="btn-blue">Verander</button>
                                </form>
                                <a class="btn-blue" href="/user/shopping-list/delete?id=<?= $item['article_id'] ?>"><img src="/images/Icon_trash.svg" alt="trash can"></a>
                            </div>
                        </article>
                        <?php
                    }
                    ?>
                </section>
                <aside>
                    <a href="/user/order" class="btn-blue">Bestellen</a>
                </aside>
            <?php endif; ?>
        </div>
    </main>
    <?php include "../../resources/footer.php" ?>
</body>

</html>