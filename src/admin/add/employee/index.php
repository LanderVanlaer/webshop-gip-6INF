<?php
    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";

    $one_empty = $passwordNotMatch = $passwordFail = $duplicate = $executed = $succes = $last_id = false;

    if (!empty($_POST)) {
        $username = var_validate($_POST['username']);
        $firstname = var_validate($_POST['firstname']);
        $lastname = var_validate($_POST['lastname']);
        $password = var_validate($_POST['password']);
        $passwordConfirm = var_validate($_POST['passwordConfirm']);

        if (!is_one_empty($username, $firstname, $lastname, $password, $passwordConfirm)) {
            $passwordFail = password_not_possible($password);

            if (!count($passwordFail)) {
                if ($password === $passwordConfirm) {
                    include "../../../includes/connection.inc.php";

                    //Check if not duplicate
                    $query = $con->prepare("SELECT id FROM `employee` WHERE username = ? LIMIT 1");
                    $query->bind_param('s', $username);

                    $query->execute();

                    $res = $query->get_result();

                    if ($res->num_rows <= 0) {
                        $query->close();

                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        $query = $con->prepare("INSERT INTO `employee`(password, username, firstname, lastname) VALUES (?, ?, ?, ?)");
                        $query->bind_param('ssss', $hashedPassword, $username, $firstname, $lastname);
                        $executed = true;
                        $succes = $query->execute();

                        if ($succes) $last_id = $con->insert_id;
                    } else {
                        //duplicate
                        $duplicate = true;
                        $last_id = $res->fetch_assoc()['id'];
                    }
                    $query->close();
                    $con->close();
                } else {
                    //password mismatch
                    $passwordNotMatch = true;
                }
            }
        } else {
            //one field empty
            $one_empty = true;
        }
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
            <?php if ($one_empty): ?>
                <div class="message">
                    Gelieve alle verplichte velden in te vullen
                </div>
            <?php elseif ($passwordNotMatch): ?>
                <div class="message">
                    De 2 wachtwoorden zijn niet gelijk aan elkaar
                </div>
            <?php elseif ($passwordFail): ?>
                <div class="message">
                    <?php foreach ($passwordFail as $s) {
                        echo "$s<br>";
                    } ?>
                </div>
            <?php elseif ($duplicate): ?>
                <div class="message">
                    Er bestaat al een werknemer met deze gebruikersnaam, id: <?= $last_id ?>
                </div>
            <?php elseif ($executed): ?>
                <?php if ($succes): ?>
                    <div class="message">
                        Werknemer toegevoegd met id: <?= $last_id ?>
                    </div>
                <?php else: ?>
                    <div class="message">
                        Er is iets misgelopen
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <fieldset>
                <legend>Werknemer</legend>
                <table>
                    <tbody>
                        <tr>
                            <td><label class="required" for="username">Gebruikersnaam</label></td>
                            <td><input required type="text" name="username" id="username" maxlength="127"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="firstname">Voornaam</label></td>
                            <td><input required type="text" name="firstname" id="firstname" maxlength="63"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="lastname">Achternaam</label></td>
                            <td><input required type="text" name="lastname" id="lastname" maxlength="63"></td>
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