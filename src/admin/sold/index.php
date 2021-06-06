<?php
    include_once "../../includes/admin/admin.inc.php";

    include_once "../../includes/connection.inc.php";
    $table_result = $con->query(file_get_contents('../../sql/admin/sold/orderarticle.select.sql'));
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../resources/admin/head.html"; ?>
    <link rel="stylesheet" href="/css/admin/table.css">
    <title>Admin - sold</title>
</head>
<body>
    <?php include "../../resources/admin/header.php"; ?>
    <main>
        <h1>Verkochte Items</h1>
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="last-row-element">id</th>
                    <th colspan="3">Artikel</th>
                    <th rowspan="2" class="last-row-element">Aantal</th>
                    <th rowspan="2" class="last-row-element">Prijs</th>
                    <th rowspan="2" class="last-row-element">Datum</th>
                </tr>
                <tr>
                    <th class="last-row-element">id</th>
                    <th class="last-row-element">naam</th>
                    <th class="last-row-element">prijs</th>
                </tr>
            </thead>
            <tbody class="color-odd-hover">
                <?php while ($row = $table_result->fetch_assoc()): ?>
                    <tr>
                        <td><a href="/admin/sold/order?id=<?= $row['order_id'] ?>"> <?= $row['order_id'] ?></a></td>
                        <td><a target="_blank" href="/article/<?= $row['article_id'] ?>"> <?= $row['article_id'] ?></a></td>
                        <td><a target="_blank" href="/article/<?= $row['article_id'] ?>"> <?= $row['name'] ?></a></td>
                        <td><a target="_blank" href="/article/<?= $row['article_id'] ?>">&euro; <?= $row['price_unit'] ?></a></td>
                        <td><a href="/admin/sold/order?id=<?= $row['order_id'] ?>"> <?= $row['amount'] ?></a></td>
                        <td><a href="/admin/sold/order?id=<?= $row['order_id'] ?>">&euro; <?= $row['price_total'] ?></a></td>
                        <td><a href="/admin/sold/order?id=<?= $row['order_id'] ?>"> <?= $row['date'] ?></a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <?php
        $con->close();
        include "../../resources/admin/footer.php";
    ?>
</body>
</html>