<?php
    include_once "../../includes/admin/admin.inc.php";
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../resources/admin/head.html"; ?>
    <link rel="stylesheet" href="/css/admin/add/index-nav.css">
    <title>Admin - Update navigation</title>
</head>
<body>
    <?php include "../../resources/admin/header.php"; ?>
    <main>
        <h1>Update</h1>
        <nav>
            <ul>
                <li><a class="btn-blue" href="brand">Merk</a></li>
                <li><a class="btn-blue" href="article">Article</a></li>
                <li><a class="btn-blue" href="employee">Employe / me</a></li>
            </ul>
        </nav>
    </main>
    <?php include "../../resources/admin/footer.php"; ?>
</body>
</html>