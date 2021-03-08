<?php
    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";

    $one_empty = $duplicate = $executed = $succes = $last_id = false;

    if (!empty($_POST)) {
        $nameD = var_validate($_POST['nameD']);
        $nameF = var_validate($_POST['nameF']);
        $nameE = var_validate($_POST['nameE']);

        if (!is_one_empty($nameD, $nameF, $nameE)) {
            include "../../../includes/connection.inc.php";

            //Check if not duplicate
            $query = $con->prepare("SELECT id FROM `category` WHERE nameD = ? OR nameF = ? OR nameE = ? LIMIT 1");
            $query->bind_param('sss', $nameD, $nameF, $nameE);

            $query->execute();

            $res = $query->get_result();

            if ($res->num_rows <= 0) {
                $query->close();

                $query = $con->prepare("INSERT INTO `category`(nameD, nameF, nameE) VALUES (?, ?, ?)");

                $query->bind_param('sss', $nameD, $nameF, $nameE);

                $executed = true;

                $succes = $query->execute();

                if ($succes) {
                    $last_id = $con->insert_id;
                    $query->close();

                    $query = $con->prepare("INSERT INTO `specification`(category_id, nameD, nameF, nameE) VALUES ($last_id, ?, ?, ?)");

                    $id = 0;
                    do {
                        $nameSpecD = var_validate($_POST["nameSpecD$id"]);
                        $nameSpecF = var_validate($_POST["nameSpecF$id"]);
                        $nameSpecE = var_validate($_POST["nameSpecE$id"]);

                        if (!is_one_empty($nameSpecD, $nameSpecF, $nameSpecE)) {
                            $query->bind_param('sss', $nameSpecD, $nameSpecF, $nameSpecE);
                            $executed = $query->execute();
                        }

                        $id++;
                    } while (isset($_POST["nameSpecD$id"]) || isset($_POST["nameSpecF$id"]) || isset($_POST["nameSpecE$id"]));
                }
            } else {
                //duplicate
                $duplicate = true;
                $last_id = $res->fetch_assoc()['id'];
            }

            $query->close();
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
    <script defer src="/js/admin/add/category-dynamic.js"></script>
    <title>Admin - Add Category</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Toevoegen categorie</h1>
        <form action="#" method="post">
            <?php if ($one_empty): ?>
                <div class="message">
                    Gelieve alle verplichte velden in te vullen
                </div>
            <?php elseif ($duplicate): ?>
                <div class="message">
                    Er bestaat al een categorie met deze naam, id: <?= $last_id ?>
                </div>
            <?php elseif ($executed): ?>
                <?php if ($succes): ?>
                    <div class="message">
                        Categorie toegevoegd met id: <?= $last_id ?>
                    </div>
                <?php else: ?>
                    <div class="message">
                        Er is iets misgelopen
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <fieldset>
                <legend>Category</legend>
                <table>
                    <tbody>
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
                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend>Specifications</legend>
                <table id="specifications">
                    <thead>
                        <tr>
                            <th>Nederlands</th>
                            <th>Frans</th>
                            <th>Engels</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </fieldset>
            <button class="btn-blue" type="submit">Submit</button>
        </form>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>