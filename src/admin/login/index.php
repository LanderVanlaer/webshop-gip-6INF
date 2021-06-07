<?php
    include_once "../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../includes/validateFunctions.inc.php";
    include_once "../../includes/basicFunctions.inc.php";
    include_once "../../includes/admin/adminFunctions.inc.php";
    include_once "../../includes/h_captcha.inc.php";

    session_start();

    if (isAdmin()) redirect("/admin");

    $error = 0;

    if (!empty($_POST)) {
        $responseData = h_captcha_verify($_POST['h-captcha-response']);

        if (!$responseData->success) {
            $error = Error::h_captcha;
            goto end;
        }

        $username = var_validate($_POST["username"]);
        $password = var_validate($_POST["password"]);

        if ($one_empty = is_one_empty($username, $password)) {
            $error = Error::empty_value;
            goto end;
        }
        include_once "../../includes/connection.inc.php";

        $query = $con->prepare("SELECT * FROM `employee` WHERE username = ? LIMIT 1");
        $query->bind_param("s", $username);
        $query->execute();

        $res = $query->get_result();
        $row = $res->fetch_assoc();

        if (!$row) {
            $error = Error::login_fail;
            $con->close();
            goto end;
        }
        $con->close();

        if (!password_verify($password, $row["password"])) {
            $error = Error::login_fail;
            goto end;
        }

        $_SESSION["admin"] = $row;
        redirect("/admin/index.php");

        end:
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/admin/admin.css">
    <link rel="stylesheet" href="/css/form.css">
    <?php include "../../resources/admin/head.html"; ?>
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
</head>

<body>
    <?php include "../../resources/admin/header.php"; ?>
    <main>
        <h1>Admin login</h1>
        <form name="login" action="#" method="post">
            <?php
                Error::print_admin_message($error);
                Error::print_message("De admin-pagina's zijn niet ontwikkeld voor kleine schermen zoals een smartphone/tablet");
            ?>
            <table>
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input type="text" name="username" id="username"></td>
                </tr>
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input type="password" name="password" id="password"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="h-captcha" data-sitekey="<?= H_CAPTCHA_API_KEY ?>"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn-blue" type="submit">Login</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    <?php include "../../resources/admin/footer.php"; ?>
</body>

</html>