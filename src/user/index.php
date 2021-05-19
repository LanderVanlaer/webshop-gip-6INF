<?php
    include_once "../includes/Error.php";

    use includes\Error as Error;

    include "../includes/user/userFunctions.inc.php";
    session_start();
    $logged_in = isLoggedIn();
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include "../resources/head.html" ?>
    <link rel="stylesheet" href="/css/form.css">
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>
        <h1>Profiel</h1>
        <?php if ($logged_in): ?>
            <a class="btn-blue" href="logout">Afmelden</a>
            <form action="/user/changePassword/" method="POST">
                <?php if (isset($_GET['error'])) Error::print_admin_message($_GET['error']); ?>
                <fieldset>
                    <legend>Verander Wachtwoord</legend>
                    <table>
                        <tr>
                            <td><label for="password">Oude Wachtwoord</label></td>
                            <td><input type="password" id="password" name="password"></td>
                        </tr>
                        <tr>
                            <td><label for="password_new">Nieuwe wachtwoord</label></td>
                            <td><input type="password" id="password_new" name="password_new"></td>
                        </tr>
                        <tr>
                            <td><label for="password_new_confirm">Bevestiging</label></td>
                            <td><input type="password" id="password_new_confirm" name="password_new_confirm"></td>
                        </tr>
                    </table>
                    <button class="btn-blue" type="submit">Verander</button>
                </fieldset>
            </form>
        <?php else: ?>
            <a class="btn-blue" href="register">Registreren</a>
            <a class="btn-blue" href="login">Aanmelden</a>
        <?php endif; ?>
    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>