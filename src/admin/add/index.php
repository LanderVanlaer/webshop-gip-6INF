<?php
    include "../../includes/admin/admin.inc.php";
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../resources/admin/head.php"; ?>
    <link rel="stylesheet" href="/css/admin/add/index-nav.css">
    <title>Admin - Add navigation</title>
</head>
<body>
    <?php include "../../resources/admin/header.php"; ?>
    <main>
        <nav>
            <ul>
                <li><a class="btn-blue" href="category">Categorie</a></li>
                <li><a class="btn-blue" href="employee">Werknemer</a></li>
            </ul>
        </nav>
    </main>
    <?php include "../../resources/admin/footer.php"; ?>
</body>
</html>