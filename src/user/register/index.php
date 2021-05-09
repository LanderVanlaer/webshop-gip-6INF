<?php
    //    https://www.php.net/manual/en/ref.mail.php#77499
    include_once "../../includes/Error.php";
    
    use includes\Error as Error;
    
    include "../../includes/validateFunctions.inc.php";
    include "../../includes/basicFunctions.inc.php";
    include "../../includes/h_captcha.inc.php";
    
    $error = $executed = $succes = $last_id = 0;
    $passwordFail = array();
    $email = $firstname = $lastname = $password = $passwordConfirm = $country = $country_id = $township = $street = $streetNumber = "";

    if (!empty($_POST)) {
        $responseData = h_captcha_verify($_POST['h-captcha-response']);

        $email = var_validate($_POST['email']);
        $firstname = var_validate($_POST['firstname']);
        $lastname = var_validate($_POST['lastname']);
        $password = var_validate($_POST['password']);
        $passwordConfirm = var_validate($_POST['passwordConfirm']);
        $country = var_validate($_POST["country"]);
        $country_id = var_validate($_POST["country_id"]);
        $township = var_validate($_POST["township"]);
        $street = var_validate($_POST["street"]);
        $streetNumber = var_validate($_POST["streetNumber"]);

        if (!$responseData->success) {
            $error = Error::h_captcha;
            goto end;
        }

        if (is_one_empty($email, $firstname, $lastname, $password, $passwordConfirm, $country_id, $township, $street, $streetNumber)) {
            $error = Error::empty_value;
            goto end;
        }

        if (!email_possible($email)) {
            $error = Error::email_not_valid;
            goto end;
        }

        $passwordFail = password_not_possible($password);
        if (count($passwordFail))
            goto end;

        if (strcmp($password, $passwordConfirm) != 0) {
            $error = Error::password_not_match;
            goto end;
        }

        $succes = true;

        //Address
        include "../../includes/connection.inc.php";

        //township
        $query = $con->prepare(file_get_contents("../../sql/address/township/select.sql"));
        $query->bind_param('si', $township, $country_id);
        $query->execute();
        $res = $query->get_result()->fetch_assoc();
        $query->close();

        if ($res) {
            $township_id = $res['id'];
        } else {
            $query = $con->prepare(file_get_contents("../../sql/address/township/insert.sql"));
            $query->bind_param('si', $township, $country_id);
            $query->execute();
            $township_id = $query->insert_id;
            $query->close();
        }

        //street
        $query = $con->prepare(file_get_contents("../../sql/address/street/select.sql"));
        $name = "%$street%";
        $query->bind_param('si', $name, $township_id);
        $query->execute();
        $res = $query->get_result()->fetch_assoc();
        $query->close();

        if ($res) {
            $street_id = $res['id'];
        } else {
            $query = $con->prepare(file_get_contents("../../sql/address/street/insert.sql"));
            $query->bind_param('si', $street, $township_id);
            $query->execute();
            $street_id = $query->insert_id;
            $query->close();
        }

        //address
        $query = $con->prepare(file_get_contents("../../sql/address/address/select.sql"));
        $query->bind_param('ii', $streetNumber, $street_id);
        $query->execute();
        $res = $query->get_result()->fetch_assoc();
        $query->close();

        if ($res) {
            $address_id = $res['id'];
        } else {
            $query = $con->prepare(file_get_contents("../../sql/address/address/insert.sql"));
            $query->bind_param('ii', $streetNumber, $street_id);
            $query->execute();
            $address_id = $query->insert_id;
            $query->close();
        }


        //CUSTOMER
        //duplicate
        $query = $con->prepare(file_get_contents("../../sql/customer/duplicate.sql"));
        $query->bind_param('s', $email);
        $query->execute();
        $duplicate = $query->get_result()->num_rows;
        $query->close();

        if ($duplicate != 0) {
            $error = Error::duplicate_customer;
            $con->close();
            goto end;
        }

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $registration_code = uniqid();

        $query = $con->prepare(file_get_contents("../../sql/customer/insert.sql"));
        $query->bind_param('sssssi', $firstname, $lastname, $email, $password_hashed, $registration_code, $address_id);
        $query->execute();
        $last_id = $query->insert_id;
        $query->close();

        //send_mail
        ini_set("SMTP", "smtp.eu.mailgun.org");
        ini_set("smtp_port", 587);

        $header = "From: no-reply@mail.gigacam.be\r\nContent-type:text/html;charset=UTF-8\r\n";
        $link = "http://{$_SERVER['SERVER_NAME']}/user/activate?code=$registration_code";
        $executed = mail(
                $email,
                "Registratie gigacam.be",
                "Beste <i>$firstname $lastname</i><br>gelieve deze email te valideren via onderstaande link:\n\t<a href='$link'>$link</a>",
                $header
        );

        $con->close();
    }
    end:
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreer</title>
    <link rel="stylesheet" href="/css/form.css">
    <?php include "../../resources/head.html" ?>
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>
    <script src='/js/user/register/country-dynamic.js' async defer></script>
    <style>
        #country-dynamic {
            font-size: 2rem;
        }

        #country-dynamic tr:hover {
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <?php include "../../resources/header.php" ?>
    <main>
        <form action="#" method="post" class="margin" autocomplete="off">
            <?php
                Error::print_admin_message($error, $executed, $succes, $last_id);
                if ($passwordFail) {
                    $str = "";
                    foreach ($passwordFail as $s) {
                        $str .= "$s<br>";
                    }
                    Error::print_message($str);
                } ?>
            <fieldset>
                <legend>Werknemer</legend>
                <table>
                    <tbody>
                        <tr>
                            <td><label class="required" for="email">Email</label></td>
                            <td><input required type="email" name="email" id="email" maxlength="127" autocomplete="no" value="<?= $email ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="firstname">Voornaam</label></td>
                            <td><input required type="text" name="firstname" id="firstname" maxlength="63" autocomplete="no" value="<?= $firstname ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="lastname">Achternaam</label></td>
                            <td><input required type="text" name="lastname" id="lastname" maxlength="63" autocomplete="no" value="<?= $lastname ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="password">Wachtwoord</label></td>
                            <td><input required type="password" name="password" id="password" minlength="8" autocomplete="no" maxlength="32"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="passwordConfirm">Wachtwoord bevestigen</label></td>
                            <td><input required type="password" name="passwordConfirm" id="passwordConfirm" minlength="8" autocomplete="no" maxlength="32"></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend>Adres</legend>
                <table>
                    <tr>
                        <td><label class="required" for="country">Land</label></td>
                        <td><input required type="text" name="country" id="country" value="<?= $country ?>">
                            <input type="hidden" name="country_id" id="country_id" autocomplete="no" value="<?= $country_id ?>></td>
                    </tr>
                    <tr>
                        <td colspan=" 2">
                            <table id="country-dynamic">
                                <!--API-COUNTRY-->
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="required" for="township">Gemeente Postcode</label></td>
                        <td><input required type="text" name="township" id="township" autocomplete="no" maxlength="7" value="<?= $township ?>"></td>
                    </tr>
                    <tr>
                        <td><label class="required" for="street">Straat</label></td>
                        <td><input required type="text" name="street" id="street" autocomplete="no" value="<?= $street ?>"></td>
                    </tr>
                    <tr>
                        <td><label class="required" for="streetNumber">Nummer</label></td>
                        <td><input required type="number" min="1" name="streetNumber" id="streetNumber" autocomplete="no" value="<?= $streetNumber ?>"></td>
                    </tr>
                </table>
            </fieldset>
            <div class="h-captcha" data-sitekey="<?= H_CAPTCHA_API_KEY ?>"></div>
            <button class="btn-blue" type="submit">Submit</button>
        </form>
    </main>
    <?php include "../../resources/footer.php" ?>
</body>

</html>