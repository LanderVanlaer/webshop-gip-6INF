<?php
    session_start();

    include_once "../../../includes/basicFunctions.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";
    include_once "../../../includes/user/userFunctions.inc.php";
    $article_id = $article_amount = null;

    if (!empty($_POST)) {
        $article_id = !isset($_POST['id']) || !is_numeric($_POST['id']) ? '' : intval(var_validate($_POST['id']));
        $article_amount = !isset($_POST['amount']) || !is_numeric($_POST['amount']) ? 1 : intval(var_validate($_POST['amount']));
    } else {
        $article_id = !isset($_GET['id']) || !is_numeric($_GET['id']) ? '' : intval(var_validate($_GET['id']));
        $article_amount = !isset($_GET['amount']) || !is_numeric($_GET['amount']) ? 1 : intval(var_validate($_GET['amount']));
    }


    if ($article_id == '' || $article_amount > 99)
        redirect($_SERVER['HTTP_REFERER'] ?: '/');
    if (!isLoggedIn())
        redirect('/user/login');

    include_once '../../../includes/connection.inc.php';

    $query = $con->prepare(file_get_contents("../../../sql/customer/shopping-list/shopping-list-article.customer.article.select.sql"));
    $query->bind_param('ii', $_SESSION['user']['id'], $article_id);
    $query->execute();
    $res = $query->get_result();
    $row = $res->fetch_assoc();

    if ($row['amount'] + $article_amount > 99)
        $article_amount = 99 - $row['amount'];

    $query = $con->prepare(file_get_contents("../../../sql/customer/shopping-list/shopping-list-article.customer-article.insert.sql"));
    $query->bind_param("iii", $_SESSION['user']['id'], $article_id, $article_amount);
    $query->execute();

    $query->close();
    $con->close();

    redirect($_SERVER['HTTP_REFERER'] ?: '/user/shopping-list');