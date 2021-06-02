<?php
    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/basicFunctions.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";
    
    if (empty($_GET['id']) || !is_numeric($_GET['id']))
        redirect("/admin/sold");
    
    include_once "../../../includes/connection.inc.php";
    
    $query = $con->prepare(file_get_contents('../../../sql/admin/sold/order-orderarticle.order.select.sql'));
    $order_id = var_validate($_GET['id']);
    $query->bind_param('i', $order_id);
    $query->execute();
    $result = $query->get_result();
    
    $first = true;
    while ($row = $result->fetch_assoc()) {
        if ($first) {
            $order = [
                    'id' => $row['order_id'],
                    'date' => $row['date'],
                    'customer' => [
                            'id' => $row['customer_id'],
                            'firstname' => $row['firstname'],
                            'lastname' => $row['lastname'],
                    ],
                    'address' => [
                            'delivery' => $row['delivery_address'],
                            'purchase' => $row['purchase_address'],
                    ],
                    'articles' => [],
                    'price_total' => 0,
                    'price_units' => 0,
                    'amount_total' => 0,
            ];
            $first = false;
        }
        
        $order['articles'][] = [
                'id' => $row['article_id'],
                'name' => $row['name'],
                'amount' => $row['amount'],
                'price' => [
                        'unit' => $row['price_unit'],
                        'total' => $row['price_total'],
                ]
        ];

        $order['price_total'] += $row['price_total'];
        $order['price_units'] += $row['price_unit'];
        $order['amount_total'] += $row['amount'];
    }
    
    $query->close();
    $con->close();
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../../resources/admin/head.php"; ?>
    <link rel="stylesheet" href="/css/admin/sold/order/style.css">
    <title>Admin - sold</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Order: <span><?= $order['id'] ?></span></h1>
        <table id="order">
            <tbody>
                <tr>
                    <th>Order</th>
                    <td><?= $order['id'] ?></td>
                    <td class="empty" rowspan="2" style="border: none"></td>
                    <th><a href="/admin/users/get?id=<?= $order['customer']['id'] ?>">Customer</a></th>
                    <td><a href="/admin/users/get?id=<?= $order['customer']['id'] ?>"><?= $order['customer']['id'] ?></a></td>
                </tr>
                <tr>
                    <th>Datum</th>
                    <td><?= $order['date'] ?></td>
                    <th><a href="/admin/users/get?id=<?= $order['customer']['id'] ?>">Voornaam</a></th>
                    <td><a href="/admin/users/get?id=<?= $order['customer']['id'] ?>"><?= $order['customer']['firstname'] ?></a></td>
                </tr>
                <tr>
                    <td class="empty" colspan="2"></td>
                    <td class="empty"></td>
                    <th><a href="/admin/users/get?id=<?= $order['customer']['id'] ?>">Achernaam</a></th>
                    <td><a href="/admin/users/get?id=<?= $order['customer']['id'] ?>"><?= $order['customer']['lastname'] ?></a></td>
                </tr>
                <tr class="empty">
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <th>Factureringsadres</th>
                    <td colspan="4"><?= $order['address']['purchase'] ?></td>
                </tr>
                <tr>
                    <th>Leveringsadres</th>
                    <td colspan="4"><?= $order['address']['delivery'] ?></td>
                </tr>
            </tbody>
        </table>
        <table id="articles">
            <caption>Articles</caption>
            <thead>
                <tr>
                    <th rowspan="2">id</th>
                    <th rowspan="2">Naam</th>
                    <th rowspan="2">Aantal</th>
                    <th colspan="2">Prijs</th>
                </tr>
                <tr>
                    <th>Stuk</th>
                    <th>Totaal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['articles'] as $article) : ?>
                    <tr>
                        <td><a href="/article/<?= $article['id'] ?>"><?= $article['id'] ?></a></td>
                        <td><a href="/article/<?= $article['id'] ?>"><?= $article['name'] ?></a></td>
                        <td><a href="/article/<?= $article['id'] ?>"><?= $article['amount'] ?></a></td>
                        <td><a href="/article/<?= $article['id'] ?>">&euro;&nbsp;<?= $article['price']['unit'] ?></a></td>
                        <td><a href="/article/<?= $article['id'] ?>">&euro;&nbsp;<?= $article['price']['total'] ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Totaal</td>
                    <td><?= $order['amount_total'] ?></td>
                    <td>&euro;&nbsp;<?= $order['price_units'] ?></td>
                    <td>&euro;&nbsp;<?= $order['price_total'] ?></td>
                </tr>
            </tfoot>
        </table>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>