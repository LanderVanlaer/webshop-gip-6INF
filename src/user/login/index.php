<?php
    include_once "../../includes/Error.php";
    
    use includes\Error as Error;

    include_once "../../includes/Mail.php";
    include_once "../../includes/validateFunctions.inc.php";
    include_once "../../includes/basicFunctions.inc.php";
    include_once "../../includes/h_captcha.inc.php";
    include_once "../../includes/user/userFunctions.inc.php";

    session_start();

    if (isLoggedIn()) redirect("/user");

    $error = 0;
    $resend_mail = false;
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
        
        $query = $con->prepare("SELECT * FROM `customer` WHERE email = ? LIMIT 1");
        $query->bind_param("s", $username);
        $query->execute();

        $res = $query->get_result();
        $row = $res->fetch_assoc();
        $query->close();

        if (!$row) {
            $error = Error::login_fail;
            $con->close();
            goto end;
        }

        if (!password_verify($password, $row["password"])) {
            $con->close();
            $error = Error::login_fail;
            goto end;
        }

        if ($row['active'] == 0) {
            $resend_mail = true;
            $error = Error::customer_not_active;
            $registration_code = $row['registration_code'];

            if (empty($registration_code)) {
                $registration_code = uniqid();
                $query = $con->prepare("UPDATE customer SET registration_code = ? WHERE id = ?");
                $query->bind_param("si", $registration_code, $row['id']);
                $query->execute();
            }
            Mail\mail_send_activate($row['email'], $row['firstname'], $row['lastname'], $registration_code);
            $con->close();
            goto end;
        }

        $con->close();
        $_SESSION["user"] = $row;

        if (empty($_GET['refer']))
            redirect("/user");

        redirect(urldecode($_GET['refer']));

        end:
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
    <link rel="stylesheet" href="/css/form.css">
    <?php include "../../resources/head.html"; ?>
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
</head>

<body>
    <?php include "../../resources/header.php"; ?>
    <main>
        <h1><?= _['title'] ?></h1>
        <form name="login" action="#" method="post">
            <?php Error::print_admin_message($error);
                if ($resend_mail) {
                    Error::print_message(str_replace("{email}", $row["email"], _['resend_mail']));
                }
            ?>
            <table class="margin-center responsive">
                <tr>
                    <td><label for="username"><?= _['username'] ?></label></td>
                    <td><input type="text" name="username" id="username"></td>
                </tr>
                <tr>
                    <td><label for="password"><?= _['password'] ?></label></td>
                    <td><input type="password" name="password" id="password"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="h-captcha" data-sitekey="<?= H_CAPTCHA_API_KEY ?>"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn-blue" type="submit"><?= _['login'] ?></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a class="btn-blue" href="forgot-password"><?= _['forgot'] ?></a>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    <?php include "../../resources/footer.php"; ?>
</body>

</html>