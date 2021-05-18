<?php
    include_once "../../includes/Error.php";
    
    use includes\Error as Error;
    
    include "../../includes/validateFunctions.inc.php";
    include "../../includes/basicFunctions.inc.php";
    include "../../includes/h_captcha.inc.php";
    include "../../includes/user/userFunctions.inc.php";

    session_start();

    if (isLoggedIn()) redirect("/user");

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
        include "../../includes/connection.inc.php";
        
        $query = $con->prepare("SELECT * FROM `customer` WHERE email = ? LIMIT 1");
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

        $_SESSION["user"] = $row;
        redirect("/user");
        
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
    <?php include "../../resources/head.html"; ?>
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
</head>

<body>
    <?php include "../../resources/header.php"; ?>
    <main>
        <h1>Admin login</h1>
        <form name="login" action="#" method="post">
            <?php Error::print_admin_message($error); ?>
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
    <?php include "../../resources/footer.php"; ?>
</body>

</html>