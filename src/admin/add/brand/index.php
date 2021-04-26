<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";
    include "../../../includes/fileFuncties.inc.php";

    $error = 0;
    $executed = $succes = $last_id = false;
    if (!empty($_POST)) {
        $name = var_validate($_POST['brandName']);
        $file = $_FILES['image'];

        if (is_one_empty($name, $file["tmp_name"])) {
            $error = Error::empty_value;
            goto end;
        }

        $fileNameEx = explode(".", $file["name"]);
        if (!is_image($file) && $file['error'] === 0) {
            $error = Error::file_not_allowed;
            goto end;
        }

        if (!file_size_less($file, 1000000)) { //1MB
            $error = Error::file_too_large;
            goto end;
        }

        include "../../../includes/connection.inc.php";

        //Check if not duplicate
        $query = $con->prepare("SELECT id FROM `brand` WHERE name = ? LIMIT 1");
        $query->bind_param('s', $name);
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

        $newFileName = file_save($file, "../../../images/brands");

        $query = $con->prepare("INSERT INTO `brand`(name, logo) VALUES (?, ?)");
        $query->bind_param('ss', $name, $newFileName);
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
    <title>Admin - Add Brand</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Toevoegen merk</h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <?php Error::print_admin_message($error, $executed, $succes, $last_id); ?>
            <fieldset>
                <legend>Merk</legend>
                <table>
                    <tbody>
                        <tr>
                            <td><label class="required" for="brandName">Naam</label></td>
                            <td><input required type="text" name="brandName" id="brandName" maxlength="31"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="image">Foto</label></td>
                            <td><input required type="file" name="image" id="image"></td>
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