<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    $error = 0;
    $passwordFail = $executed = $succes = $last_id = false;

    $username = $firstname = $lastname = $password = $passwordConfirm = "";
    if (!empty($_POST)) {
        $username = var_validate($_POST['username']);
        $firstname = var_validate($_POST['firstname']);
        $lastname = var_validate($_POST['lastname']);
        $password = var_validate($_POST['password']);
        $passwordConfirm = var_validate($_POST['passwordConfirm']);

        if (is_one_empty($username, $firstname, $lastname, $password, $passwordConfirm)) {
            $error = Error::empty_value;
            goto end;
        }

        $passwordFail = password_not_possible($password);
        if (count($passwordFail)) {
            goto end;
        }

        if ($password != $passwordConfirm) {
            $error = Error::password_not_match;
            goto end;
        }

        include_once "../../../includes/connection.inc.php";

        //Check if not duplicate
        $query = $con->prepare("SELECT id FROM `employee` WHERE username = ? LIMIT 1");
        $query->bind_param('s', $username);

        $query->execute();

        $res = $query->get_result();

        if ($res->num_rows > 0) {
            $error = Error::duplicate;
            $last_id = $res->fetch_assoc()['id'];
            $query->close();
            $con->close();
            goto end;
        }
        $query->close();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = $con->prepare("INSERT INTO `employee`(password, username, firstname, lastname) VALUES (?, ?, ?, ?)");
        $query->bind_param('ssss', $hashedPassword, $username, $firstname, $lastname);
        $executed = true;
        $succes = $query->execute();

        if ($succes) $last_id = $con->insert_id;

        $query->close();
        $con->close();
        end:
    }
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../../resources/admin/head.php"; ?>
    <link rel="stylesheet" href="/css/admin/add/style.css">
    <link rel="stylesheet" href="/css/form.css">
    <title>Admin - Add Werknemer</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Toevoegen werknemer</h1>
        <form action="#" method="post">
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
                            <td><label class="required" for="username">Gebruikersnaam</label></td>
                            <td><input required type="text" name="username" id="username" maxlength="127" value="<?= $username ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="firstname">Voornaam</label></td>
                            <td><input required type="text" name="firstname" id="firstname" maxlength="63" value="<?= $firstname ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="lastname">Achternaam</label></td>
                            <td><input required type="text" name="lastname" id="lastname" maxlength="63" value="<?= $lastname ?>"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="password">Wachtwoord</label></td>
                            <td><input required type="password" name="password" id="password" minlength="8" maxlength="32"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="passwordConfirm">Wachtwoord bevestigen</label></td>
                            <td><input required type="password" name="passwordConfirm" id="passwordConfirm" minlength="8" maxlength="32"></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <button class="btn-blue" type="submit">Submit</button>
        </form>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>