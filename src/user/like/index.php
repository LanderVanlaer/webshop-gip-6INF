<?php
    session_start();

    include "../../includes/user/userFunctions.inc.php";
    include "../../includes/validateFunctions.inc.php";
    include "../../includes/basicFunctions.inc.php";

    $article_id = empty($_GET['id']) ? -1 : var_validate($_GET['id']);

    if (!isLoggedIn())
        redirect("/user/login?refer=" . urlencode("/user/like?id=$article_id"));

    if (!is_numeric($article_id))
        redirect("/");

    $article_id = intval($article_id);

    include "../../includes/connection.inc.php";
    $query = $con->prepare("SELECT COUNT(*) AS amount FROM `like` WHERE article_id = ? AND customers_id = ?");
    $query->bind_param('ii', $article_id, $_SESSION['user']['id']);
    $query->execute();
    $res = $query->get_result();
    $row = $res->fetch_assoc();
    $query->close();

    if ($row['amount'] == 0) {
        $query = $con->prepare("INSERT INTO `like`(article_id, customers_id) VALUES (?, ?)");
    } else {
        $query = $con->prepare("DELETE FROM `like` WHERE article_id = ? AND customers_id = ? LIMIT 1");
    }
    $query->bind_param('ii', $article_id, $_SESSION['user']['id']);
    $query->execute();
    $query->close();


    $con->close();

    redirect("/article/$article_id");