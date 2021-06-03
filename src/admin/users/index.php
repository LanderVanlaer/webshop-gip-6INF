<?php
    include_once "../../includes/admin/admin.inc.php";

    include_once "../../includes/connection.inc.php";
    $table_result = $con->query(file_get_contents('../../sql/admin/users/customer.select.sql'));
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../resources/admin/head.php"; ?>
    <link rel="stylesheet" href="/css/admin/table.css">
    <style>
        table td a {
            padding: 1rem;
        }
    </style>
</head>
<body>
    <?php include "../../resources/admin/header.php"; ?>
    <main>
        <h1>Customers</h1>
        <div>
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">id</th>
                        <th colspan="2">Naam</th>
                        <th rowspan="2">Email</th>
                        <th rowspan="2">Registratie-code</th>
                        <th rowspan="2">Actief</th>
                    </tr>
                    <tr>
                        <th>Voor-</th>
                        <th>Achter-</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $table_result->fetch_assoc()): ?>
                        <tr>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['id'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['firstname'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['lastname'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['email'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['registration_code'] ?></a></td>
                            <td><a href="get?id=<?= $row['id'] ?>"><?= $row['active'] ?></a></td>
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