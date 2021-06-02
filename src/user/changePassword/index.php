<?php
    session_start();
    include_once "../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../includes/user/userFunctions.inc.php";
    include_once "../../includes/basicFunctions.inc.php";
    include_once "../../includes/validateFunctions.inc.php";

    if (empty($_POST) || !isLoggedIn())
        redirect("/user/login");

    $password = var_validate($_POST['password']);
    $password_new = var_validate($_POST['password_new']);
    $password_new_confirm = var_validate($_POST['password_new_confirm']);

    if (strcmp($password_new, $password_new_confirm)) {
        redirect('/user?error=' . Error::password_not_match);
        die();
    }

    if (count(password_not_possible($password_new))) {
        redirect('/user?error=' . Error::password_not_possible);
        die();
    }

    include_once "../../includes/connection.inc.php";

    $query = $con->prepare("SELECT * FROM `customer` WHERE id = ? LIMIT 1");
    $query->bind_param("i", $_SESSION['user']['id']);
    $query->execute();

    $res = $query->get_result();
    $row = $res->fetch_assoc();

    if (!password_verify($password, $row["password"])) {
        $con->close();
        redirect("/user?error=" . Error::login_fail);
        die();
    }

    $query->close();

    $query = $con->prepare("UPDATE customer SET password = ? WHERE id = ?");
    $hash = password_hash($password_new, PASSWORD_DEFAULT);
    $query->bind_param("si", $hash, $_SESSION['user']['id']);
    $query->execute();
    $rows = $query->affected_rows;
    $query->close();
    $con->close();

    if (!$rows) {
        redirect("/user?error=" . Error::internal);
        die();
    }

    redirect('/user');
