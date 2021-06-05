<?php
    //    https://www.php.net/manual/en/ref.mail.php#77499
    include_once "../../includes/Error.php";

    use includes\Error as Error;
    
    include_once "../../includes/Mail.php";
    include_once "../../includes/validateFunctions.inc.php";
    include_once "../../includes/basicFunctions.inc.php";
    include_once "../../includes/h_captcha.inc.php";
    include_once "../../includes/database/add.inc.php";

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

        if (is_one_empty($email, $firstname, $lastname, $password, $passwordConfirm, $country_id, $township, $street, $streetNumber) || !is_numeric($streetNumber)) {
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
        include_once "../../includes/connection.inc.php";

        $address_id = db_add_address($con, $country_id, $township, $street, $streetNumber);

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
        $executed = Mail\mail_send_activate($email, $firstname, $lastname, $registration_code);

        $con->close();
    }
    end:
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
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
        <h1><?= _['title'] ?></h1>
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
                <legend><?= _['info'] ?></legend>
                <table>
                    <tbody>
                        <tr>
                            <td><label class="required" for="email"><?= _['email'] ?></label></td>
                            <td><input required type="email" name="email" id="email" maxlength="127" autocomplete="no" value="<?= $email ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="firstname"><?= _['firstname'] ?></label></td>
                            <td><input required type="text" name="firstname" id="firstname" maxlength="63" autocomplete="no" value="<?= $firstname ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="lastname"><?= _['lastname'] ?></label></td>
                            <td><input required type="text" name="lastname" id="lastname" maxlength="63" autocomplete="no" value="<?= $lastname ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="password"><?= _['password'] ?></label></td>
                            <td><input required type="password" name="password" id="password" minlength="8" autocomplete="no" maxlength="32"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="passwordConfirm"><?= _['password_confirm'] ?></label></td>
                            <td><input required type="password" name="passwordConfirm" id="passwordConfirm" minlength="8" autocomplete="no" maxlength="32"></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend><?= _['address'] ?></legend>
                <table>
                    <tr>
                        <td><label class="required" for="country"><?= _['country'] ?></label></td>
                        <td><input required type="text" name="country" id="country" autocomplete="no" value="<?= $country_id ? $country : "" ?>">
                            <input type="hidden" name="country_id" id="country_id" autocomplete="no" value="<?= $country_id ?>"></td>
                    </tr>
                    <tr>
                        <td colspan=" 2">
                            <table id="country-dynamic">
                                <!--API-COUNTRY-->
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="required" for="township"><?= _['postal_code'] ?></label></td>
                        <td><input required type="text" name="township" id="township" autocomplete="no" maxlength="7" value="<?= $township ?>"></td>
                    </tr>
                    <tr>
                        <td><label class="required" for="street"><?= _['street'] ?></label></td>
                        <td><input required type="text" name="street" id="street" autocomplete="no" value="<?= $street ?>"></td>
                    </tr>
                    <tr>
                        <td><label class="required" for="streetNumber"><?= _['number'] ?></label></td>
                        <td><input required type="number" min="1" name="streetNumber" id="streetNumber" autocomplete="no" value="<?= $streetNumber ?>"></td>
                    </tr>
                </table>
            </fieldset>
            <div class="h-captcha" data-sitekey="<?= H_CAPTCHA_API_KEY ?>"></div>
            <button class="btn-blue" type="submit"><?= _['create'] ?></button>
        </form>
    </main>
    <?php include "../../resources/footer.php" ?>
</body>

</html>