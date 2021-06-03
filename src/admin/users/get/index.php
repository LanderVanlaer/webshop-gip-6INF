<?php
    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/basicFunctions.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    if (empty($_GET['id']) || !is_numeric($_GET['id']))
        redirect("/admin/users");

    include_once "../../../includes/connection.inc.php";

    $query = $con->prepare(file_get_contents('../../../sql/admin/users/customer.customer.select.sql'));
    $customer_id = var_validate($_GET['id']);
    $query->bind_param('i', $customer_id);
    $query->execute();

    $result = $query->get_result();
    $row = $result->fetch_assoc();

    $customer = [
            'id' => $customer_id,
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'email' => $row['email'],
            'registration_code' => $row['registration_code'],
            'active' => $row['active'],
            'address' => $row['address'],
            'orders' => [],
            'total_orders' => 0,
            'total_articles' => 0
    ];
    $query->close();


    $query = $con->prepare(file_get_contents('../../../sql/admin/users/order.customer.select.sql'));
    $customer_id = var_validate($_GET['id']);
    $query->bind_param('i', $customer_id);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_assoc()) {
        $customer['orders'][] = [
                'id' => $row['id'],
                'date' => $row['date'],
                'amount' => $row['amount'],
        ];

        $customer['total_orders'] += 1;
        $customer['total_articles'] += $row['amount'];
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
    <link rel="stylesheet" href="/css/admin/table.css">
    <link rel="stylesheet" href="/css/admin/users/get/style.css">
    <title>Admin - User</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Customer: <span><?= $customer['id'] ?></span></h1>
        <div id="customer">
            <table>
                <tbody>
                    <tr>
                        <th>Id</th>
                        <td colspan="3"><?= $customer['id'] ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td colspan="3"><?= $customer['email'] ?></td>
                    </tr>
                    <tr>
                        <th>Voornaam</th>
                        <td><?= $customer['firstname'] ?></td>
                        <th>Achternaam</th>
                        <td><?= $customer['lastname'] ?></td>
                    </tr>
                    <tr>
                        <th>Adres</th>
                        <td colspan="3"><?= $customer['address'] ?></td>
                    </tr>
                    <tr class="empty">
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <th>Registratiecode</th>
                        <td colspan="3"><?= $customer['registration_code'] ?></td>
                    </tr>
                    <tr>
                        <th>Actief</th>
                        <td colspan="3"><?= $customer['active'] ?></td>
                    </tr>
                    <tr>
                        <th>Totaal orders</th>
                        <td><?= $customer['total_orders'] ?></td>
                        <th>Totaal artikelen</th>
                        <td><?= $customer['total_articles'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="orders">
            <table>
                <caption>Orders</caption>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Datum</th>
                        <th>Aantal</th>
                    </tr>
                </thead>
                <tbody class="color-odd-hover">
                    <?php foreach ($customer['orders'] as $order): ?>
                        <tr>
                            <td><a href="/admin/sold/order?id=<?= $order['id'] ?>"><?= $order['id'] ?></a></td>
                            <td><a href="/admin/sold/order?id=<?= $order['id'] ?>"><?= $order['date'] ?></a></td>
                            <td><a href="/admin/sold/order?id=<?= $order['id'] ?>"><?= $order['amount'] ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>