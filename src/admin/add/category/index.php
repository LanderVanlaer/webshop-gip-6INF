<?php
    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";

    $one_empty = $executed = $succes = false;

    if (!empty($_POST)) {
        $nameD = var_validate($_POST['nameD']);
        $nameF = var_validate($_POST['nameF']);
        $nameE = var_validate($_POST['nameE']);

        if (!is_one_empty($nameD, $nameF, $nameE)) {
            include "../../../includes/connection.inc.php";

            $query = $con->prepare("INSERT INTO `category`(nameD, nameF, nameE) VALUES (?, ?, ?)");

            $query->bind_param('sss', $nameD, $nameF, $nameE);

            $executed = true;

            $succes = $query->execute();

            $con->close();
        } else {
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
    <title>Admin - Add Category</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Toevoegen categorie</h1>
        <form action="#" method="post">
            <?php if ($one_empty) { ?>
                <div class="message">
                    Gelieve alle verplichte velden in te vullen
                </div>
            <?php } else if ($executed) { ?>
                <div class="message">
                    <?= $succes ? "Succes" : "Failed"; ?>
                </div>
            <?php } ?>
            <table>
                <tr>
                    <td><label class="required" for="nameD">Naam Nederlands</label></td>
                    <td><input required type="text" name="nameD" id="nameD" maxlength="31"></td>
                </tr>
                <tr>
                    <td><label class="required" for="nameF">Naam Frans</label></td>
                    <td><input required type="text" name="nameF" id="nameF" maxlength="31"></td>
                </tr>
                <tr>
                    <td><label class="required" for="nameE">Naam English</label></td>
                    <td><input required type="text" name="nameE" id="nameE" maxlength="31"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn-blue" type="submit">Sumit</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>