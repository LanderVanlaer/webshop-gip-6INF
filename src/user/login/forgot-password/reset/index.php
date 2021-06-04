<?php
    session_start();
    include_once "../../../../includes/Error.php";
    
    use includes\Error as Error;
    
    include_once "../../../../includes/Mail.php";
    include_once "../../../../includes/basicFunctions.inc.php";
    include_once "../../../../includes/validateFunctions.inc.php";
    include_once "../../../../includes/user/userFunctions.inc.php";
    
    $error = 0;
    $passwordFail = [];
    $code = empty($_GET["code"]) ?
            (empty($_POST["code"]) ?
                    ""
                    : var_validate($_POST["code"]))
            : var_validate($_GET["code"]);
    
    if (empty($code))
        redirect("/user");

    if (!empty($_POST)) {
        $password = var_validate($_POST["password"]);
        $password_confirm = var_validate($_POST["password_confirm"]);

        if (is_one_empty($password, $password_confirm)) {
            $error = Error::empty_value;
            goto end;
        }

        if (strcmp($password, $password_confirm) != 0) {
            $error = Error::password_not_match;
            goto end;
        }

        $passwordFail = password_not_possible($password);
        if (count($passwordFail))
            goto end;

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        include_once "../../../../includes/connection.inc.php";
        $query = $con->prepare("UPDATE customer SET registration_code = null, password= ? WHERE registration_code = ? LIMIT 1;");
        $query->bind_param("ss", $password_hashed, $code);
        $query->execute();

        if ($query->affected_rows <= 0) {
            $query->close();
            $con->close();
            $error = Error::bad_request;
            goto end;
        }

        $query->close();
        $con->close();

        redirect("/user/login");
    }
    end:
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
    <?php include "../../../../resources/head.html" ?>
    <link rel="stylesheet" href="/css/form.css">
</head>

<body>
    <?php include "../../../../resources/header.php" ?>
    <main>
        <h1><?= _['title'] ?></h1>
        <form action="#" method="post">
            <?php
                Error::print_admin_message($error);
                if ($passwordFail) {
                    $str = "";
                    foreach ($passwordFail as $s) $str .= "$s<br>";
                    Error::print_message($str);
                }
            ?>
            <input type="hidden" name="code" value="<?= $code ?>">
            <table class="margin-center">
                <tr>
                    <td><label class="required" for="password"><?= _['password'] ?>: </label></td>
                    <td><input id="password" name="password" required type="password"></td>
                </tr>
                <tr>
                    <td><label class="required" for="password_confirm"><?= _['password_confirm'] ?>: </label></td>
                    <td><input id="password_confirm" name="password_confirm" required type="password"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn-blue" type="submit"><?= _['change'] ?></button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    <?php include "../../../../resources/footer.php" ?>
</body>

</html>