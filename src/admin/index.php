<?php
    include "../includes/basicFunctions.inc.php";
    session_start();

    if (!isAdmin()) redirect("/admin/login");
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../resources/admin/head.php"; ?>
    <title>Admin</title>
</head>
<body>
    <?php include "../resources/admin/header.php"; ?>
    <main>
    </main>
    <?php include "../resources/admin/footer.php"; ?>
</body>
</html>