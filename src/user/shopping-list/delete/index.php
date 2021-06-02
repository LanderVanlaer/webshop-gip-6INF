<?php /** @noinspection DuplicatedCode */
    session_start();

    include_once "../../../includes/basicFunctions.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";
    include_once "../../../includes/user/userFunctions.inc.php";

    $article_id = !isset($_GET['id']) || !is_numeric($_GET['id']) ? '' : intval(var_validate($_GET['id']));


    if ($article_id == '')
        redirect($_SERVER['HTTP_REFERER'] ?: '/');
    if (!isLoggedIn())
        redirect('/user/login');

    include_once '../../../includes/connection.inc.php';
    $query = $con->prepare(file_get_contents("../../../sql/customer/shopping-list/shopping-list-article.customer-article.delete.sql"));
    $query->bind_param("ii", $_SESSION['user']['id'], $article_id);
    $query->execute();

    $query->close();
    $con->close();

    redirect($_SERVER['HTTP_REFERER'] ?: '/user/shopping-list');