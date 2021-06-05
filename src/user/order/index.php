<?php
    session_start();
    include_once "../../includes/Error.php";
    
    use includes\Error as Error;
    
    include_once "../../includes/user/userFunctions.inc.php";
    include_once "../../includes/basicFunctions.inc.php";
    include_once "../../includes/validateFunctions.inc.php";
    include_once "../../includes/database/add.inc.php";

    if (!isLoggedIn())
        redirect("/user/login");

    //get items in shoppinglist
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
        ];
    }

    if (!count($shopping_items)) {
        $con->close();
        redirect("/");
    }

    $error = 0;
    $executed = $succes = false;
    $purchase_country = $purchase_country_id = $purchase_township = $purchase_street = $purchase_streetNumber = "";
    $sameAddress = $delivery_country = $delivery_country_id = $delivery_township = $delivery_street = $delivery_streetNumber = "";

    if (empty($_POST))
        goto end;

    $purchase_country = var_validate($_POST['purchase_country']);
    $purchase_country_id = var_validate($_POST['purchase_country_id']);
    $purchase_township = var_validate($_POST['purchase_township']);
    $purchase_street = var_validate($_POST['purchase_street']);
    $purchase_streetNumber = var_validate($_POST['purchase_streetNumber']);


    $sameAddress = !empty($_POST['same_address']) && strtoupper($_POST['same_address']) == 'ON';
    
    if (!$sameAddress) {
        $delivery_country = var_validate($_POST['delivery_country']);
        $delivery_country_id = var_validate($_POST['delivery_country_id']);
        $delivery_township = var_validate($_POST['delivery_township']);
        $delivery_street = var_validate($_POST['delivery_street']);
        $delivery_streetNumber = var_validate($_POST['delivery_streetNumber']);
    }

    if (is_one_empty($purchase_country_id, $purchase_township, $purchase_street, $purchase_streetNumber)
            || (!$sameAddress && is_one_empty($delivery_country, $delivery_country_id, $delivery_township, $delivery_street, $delivery_streetNumber))) {
        $error = Error::empty_value;
        goto end;
    }

    $purchase_address_id = db_add_address($con, $purchase_country_id, $purchase_township, $purchase_street, $purchase_streetNumber);

    $delivery_address_id = $purchase_address_id;
    if (!$sameAddress) {
        $delivery_address_id = db_add_address($con, $delivery_country_id, $delivery_township, $delivery_street, $delivery_streetNumber);
    }


    $executed = true;

    //    ORDER
    $query = $con->prepare(file_get_contents("../../sql/customer/order/order.address-customer.insert.sql"));
    $query->bind_param('iii', $purchase_address_id, $delivery_address_id, $_SESSION['user']['id']);
    $query->execute();
    $order_id = $query->insert_id;
    $query->close();

    //    ORDER ARTICLES
    $query = $con->prepare(file_get_contents("../../sql/customer/order/orderarticle.order-article.insert.sql"));
    $query_article_id = $query_amount = 0;
    $query->bind_param('iii', $order_id, $query_article_id, $query_amount);
    foreach ($shopping_items as $item) {
        $query_amount = $item['amount'];
        $query_article_id = $item['article_id'];

        $query->execute();
    }
    $query->close();

    $query = $con->prepare(file_get_contents("../../sql/customer/shopping-list/shopping-list-article.customer.delete.sql"));
    $query->bind_param('i', $_SESSION['user']['id']);
    $query->execute();
    $query->close();

    $succes = true;
    //    ------------------------
    end:
    $con->close();
    if ($succes)
        redirect("/");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
    <?php include "../../resources/head.html" ?>
    <link rel="stylesheet" href="/css/user/order/style.css">
    <link rel="stylesheet" href="/css/form.css">
    <script defer src="/js/user/order/address.js"></script>
    <script defer src="/js/user/order/sameAddress.js"></script>
</head>

<body>
    <?php include "../../resources/header.php" ?>
    <main>
        <h1><?= _['title'] ?></h1>
        <div class="split">
            <section class="left">
                <form action="index.php" method="post">
                    <?php Error::print_admin_message($error, $executed, $succes); ?>
                    <fieldset>
                        <legend><?= _['billing_address'] ?></legend>
                        <table>
                            <tr>
                                <td><label class="required" for="purchase_country"><?= _['country'] ?></label></td>
                                <td><input required type="text" name="purchase_country" id="purchase_country" autocomplete="no"
                                           value="<?= $purchase_country_id ? $purchase_country : "" ?>">
                                    <input type="hidden" name="purchase_country_id" id="purchase_country_id" autocomplete="no" value="<?= $purchase_country_id ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table id="purchase-country-dynamic">
                                        <!--API-COUNTRY-->
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="required" for="purchase_township"><?= _['postal_code'] ?></label></td>
                                <td><input required type="text" name="purchase_township" id="purchase_township" autocomplete="no" maxlength="7" value="<?= $purchase_township ?>">
                                </td>
                            </tr>
                            <tr>
                                <td><label class="required" for="purchase_street"><?= _['street'] ?></label></td>
                                <td><input required type="text" name="purchase_street" id="purchase_street" autocomplete="no" value="<?= $purchase_street ?>"></td>
                            </tr>
                            <tr>
                                <td><label class="required" for="purchase_streetNumber"><?= _['number'] ?></label></td>
                                <td><input required type="number" min="1" name="purchase_streetNumber" id="purchase_streetNumber" autocomplete="no"
                                           value="<?= $purchase_streetNumber ?>"></td>
                            </tr>
                        </table>
                    </fieldset>
                    <fieldset>
                        <legend><?= _['delivery_address'] ?></legend>
                        <label>
                            <?= _['same_address'] ?>
                            <input type="checkbox" name="same_address" id="same-address">
                            <span class="checkbox-custom"></span>
                        </label>
                        <table id="delivery-address-table">
                            <tr>
                                <td><label class="required" for="delivery_country"><?= _['country'] ?></label></td>
                                <td><input required type="text" name="delivery_country" id="delivery_country" autocomplete="no"
                                           value="<?= $delivery_country_id ? $delivery_country : "" ?>">
                                    <input type="hidden" name="delivery_country_id" id="delivery_country_id" autocomplete="no" value="<?= $delivery_country_id ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table id="delivery-country-dynamic">
                                        <!--API-COUNTRY-->
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><label class="required" for="delivery_township"><?= _['postal_code'] ?></label></td>
                                <td><input required type="text" name="delivery_township" id="delivery_township" autocomplete="no" maxlength="7" value="<?= $delivery_township ?>">
                                </td>
                            </tr>
                            <tr>
                                <td><label class="required" for="delivery_street"><?= _['street'] ?></label></td>
                                <td><input required type="text" name="delivery_street" id="delivery_street" autocomplete="no" value="<?= $delivery_street ?>"></td>
                            </tr>
                            <tr>
                                <td><label class="required" for="delivery_streetNumber"><?= _['number'] ?></label></td>
                                <td><input required type="number" min="1" name="delivery_streetNumber" id="delivery_streetNumber" autocomplete="no"
                                           value="<?= $delivery_streetNumber ?>"></td>
                            </tr>
                        </table>
                    </fieldset>
                    <button class="btn-blue" type="submit"><?= _['order'] ?></button>
                </form>
            </section>
            <aside class="right">
                <?php foreach ($shopping_items as $item): ?>
                    <article class="divide">
                        <div class="left">
                            <a href="/article/<?= $item['article_id'] ?>" target="_blank" class="bg-image"
                               style="background-image: url('/images/articles/<?= $item['image_path'] ?>')"></a>
                            <p><?= $item['name'] ?></p>
                        </div>
                        <div class="right">
                            <?= _['amount'] ?> <span><?= $item['amount'] ?></span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </aside>
        </div>
    </main>
    <?php include "../../resources/footer.php" ?>
</body>

</html>