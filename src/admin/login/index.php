<?php
    include "../../includes/validateFunctions.inc.php";
    include "../../includes/basicFunctions.inc.php";
    include "../../includes/admin/adminFunctions.inc.php";

    define("H_CAPTCHA_API_KEY", '3e7a1904-27c7-4bfc-b392-99c68f690f23');

    session_start();

    if (isAdmin()) redirect("/admin");

    $one_empty = $not_found = $hcaptchaFail = false;

    if (!empty($_POST)) {
        $data = array(
                'secret' => "0x1D3C92B9e7D52594976A623BFE8f870884e4A154",
                'response' => $_POST['h-captcha-response']
        );
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);

        $responseData = json_decode($response);
        if ($responseData->success) {
            $username = var_validate($_POST["username"]);
            $password = var_validate($_POST["password"]);

            if (!$one_empty = is_one_empty($username, $password)) {
                include "../../includes/connection.inc.php";

                $query = $con->prepare("SELECT * FROM `employee` WHERE username = ? LIMIT 1");
                $query->bind_param("s", $username);
                $query->execute();

                $res = $query->get_result();

                if ($row = $res->fetch_assoc()) {

                    if (password_verify($password, $row["password"])) {
                        $_SESSION["admin"] = $row;
                        redirect("/admin/index.php");
                    } else $not_found = true;

                } else $not_found = true;

                $con->close();
            }
        } else {
            $hcaptchaFail = true;
        }
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
    <?php include "../../resources/admin/head.php"; ?>
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
</head>

<body>
    <?php include "../../resources/admin/header.php"; ?>
    <main>
        <h1>Admin login</h1>
        <form name="login" action="#" method="post">
            <?php if ($one_empty) { ?>
                <div class="message">
                    Gelieve alle verplichte velden in te vullen
                </div>
            <?php } elseif ($not_found) { ?>
                <div class="message">
                    Wachtwoord of username fout
                </div>
            <?php } elseif ($hcaptchaFail) { ?>
                <div class="message">
                    Gelieve de hCaptcha in te vullen
                </div>
            <?php } ?>
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