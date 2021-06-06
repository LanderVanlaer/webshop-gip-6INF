<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    $id = empty($_GET['brand-id']) ? "" : var_validate($_GET['brand-id']);
    $error = empty($_GET['err']) ? 0 : $_GET['err'];


    $brand = [];

    include_once "../../../includes/connection.inc.php";
    $query = $con->prepare("SELECT * FROM brand WHERE id = ?");
    $query->bind_param('i', $id);
    $query->execute();

    $brand = $query->get_result()->fetch_assoc();
    if (empty($brand))
        $id = 0;
    $query->close();
    $con->close();
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../../resources/admin/head.html"; ?>
    <link rel="stylesheet" href="/css/form.css">
    <title>Admin - Update Brand</title>
    <style>
        table td img {
            max-width: 10rem;
            max-height: 5rem;
        }
    </style>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Update Brand</h1>
        <form action="#" method="get">
            <?php Error::print_admin_message($error); ?>
            <fieldset>
                <table class="margin-center">
                    <tr>
                        <td><label class="required" for="brand-id">Id:</label></td>
                        <td><input type="number" name="brand-id" id="brand-id" required value="<?= $id ?>" min="0"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn-blue" type="submit">Update</button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <?php if (!empty($id)): ?>
            <form action="name.php" method="post">
                <input type="hidden" name="brand-id" value="<?= $id ?>">
                <fieldset>
                    <legend>Naam</legend>
                    <table>
                        <tbody>
                            <tr>
                                <th><label for="name">Naam</label></th>
                                <td><input required type="text" name="name" id="name" maxlength="31" value="<?= $brand['name'] ?>"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <button type="submit" class="btn-blue">Wijzig</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </fieldset>
            </form>
            <form action="image.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="brand-id" value="<?= $id ?>">
                <fieldset>
                    <legend>Afbeelding</legend>
                    <table>
                        <tbody>
                            <tr>
                                <th><label for="image">Afbeelding</label></th>
                                <td><img src="/images/brands/<?= $brand['logo'] ?>" alt="Logo <?= $brand['name'] ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input required type="file" name="image" id="image"></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <button type="submit" class="btn-blue">Wijzig</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </fieldset>
            </form>
        <?php endif; ?>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>