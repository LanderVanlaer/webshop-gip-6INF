<?php
    include_once "../../includes/admin/admin.inc.php";

    include_once "../../includes/connection.inc.php";
    $table_result = $con->query(file_get_contents('../../sql/admin/articles/article.select.sql'));
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../resources/admin/head.php"; ?>
    <link rel="stylesheet" href="/css/admin/table.css">
    <title>Admin - Articles</title>
    <style>
        table td a {
            padding: 1rem;
        }
    </style>
</head>
<body>
    <?php include "../../resources/admin/header.php"; ?>
    <main>
        <h1>Articles</h1>
        <div>
            <table>
                <thead>
                    <tr>
                        <th colspan="5">Artikel</th>
                        <th colspan="2">Merk</th>
                    </tr>
                    <tr>
                        <th>Id</th>
                        <th>Naam</th>
                        <th>Prijs</th>
                        <th>Visible</th>
                        <th>Aanmaakdatum</th>
                        <th>Id</th>
                        <th>Naam</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $table_result->fetch_assoc()): ?>
                        <tr>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['id'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['name'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['price'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['visible'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['create_date'] ?></a></td>
                            <td><a href="/admin/brands/get?id=<?= $row['id'] ?>"><?= $row['brand_id'] ?></a></td>
                            <td><a href="/admin/brands/get?id=<?= $row['id'] ?>"><?= $row['brand_name'] ?></a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php $con->close();
        include "../../resources/admin/footer.php"; ?>
</body>
</html>