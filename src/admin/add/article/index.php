<?php
    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";
    
    define("ALLOWED_FILES", array('jpg', 'jpeg', 'png', 'svg'));
    
    $one_empty = $notAllowedFile = $fileTooLarge = $executed = $succes = $last_id = false;
    if (!empty($_POST)) {
        $name = var_validate($_POST["name"]);
        $brand = var_validate($_POST["brand"]);
        $price = var_validate($_POST["price"]);
        
        $descriptionD = var_validate($_POST["descriptionD"]);
        $descriptionF = var_validate($_POST["descriptionF"]);
        $descriptionE = var_validate($_POST["descriptionE"]);
        
        $thumbnailImage = $_FILES['thumbnailImage'];
        $images = $_FILES['images'];
        
        print_r($_FILES);
        
        if (!is_one_empty($name) && false) {
            $fileNameEx = explode(".", $file["name"]);
            if (in_array(end($fileNameEx), ALLOWED_FILES) && $file['error'] === 0) {
                if ($file['size'] <= 1000000) { //1MB
                    include "../../../includes/connection.inc.php";
                    
                    //Check if not duplicate
                    $query = $con->prepare("SELECT id FROM `brand` WHERE name = ? LIMIT 1");
                    $query->bind_param('s', $name);
                    $query->execute();
                    $res = $query->get_result();
                    
                    $query->close();
                    
                    //$name . strtolower(end($fileNameEx)); //WARNING
                    $newFileName = uniqid('', true) . '.' . strtolower(end($fileNameEx));
                    $fileDestination = "../../../images/brands/$newFileName";
                    move_uploaded_file($file['tmp_name'], $fileDestination);
                    
                    $query = $con->prepare("INSERT INTO `brand`(name, logo) VALUES (?, ?)");
                    $query->bind_param('ss', $name, $newFileName);
                    $executed = true;
                    $succes = $query->execute();
                    
                    if ($succes) $last_id = $con->insert_id;
                    $query->close();
                    $con->close();
                } else {
                    //File too large
                    $fileTooLarge = true;
                }
            } else {
                //not allowed file
                $notAllowedFile = true;
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
    <title>Admin - Add article</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Toevoegen artikel</h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <?php if ($one_empty): ?>
                <div class="message">
                    Gelieve alle verplichte velden in te vullen
                </div>
            <?php elseif ($notAllowedFile): ?>
                <div class="message">
                    U kan geen bestanden uploaden van dit type
                </div>
            <?php elseif ($fileTooLarge): ?>
                <div class="message">
                    Het bestand is te groot
                </div>
            <?php elseif ($executed): ?>
                <?php if ($succes): ?>
                    <div class="message">
                        Merk toegevoegd met id: <?= $last_id ?>
                    </div>
                <?php else: ?>
                    <div class="message">
                        Er is iets misgelopen
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <fieldset>
                <legend>Artikel</legend>
                <table>
                    <tbody>
                        <tr>
                            <td><label class="required" for="name">Naam</label></td>
                            <td colspan="3"><input required type="text" name="name" id="name" maxlength="31"></td>
                        </tr>
                        <tr>
                            <td><label class="required" for="brand">Merk</label></td>
                            <td>
                                <input type="text" name="brand" id="brand">
                                <input class="none" type="number" name="brand_id">
                            </td>
                            <td><label class="required" for="price">Prijs</label></td>
                            <td><input type="number" name="price" id="price"></td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend>Beschrijving</legend>
                <table>
                    <tr>
                        <td><label for="descriptionD">Nederlands</label></td>
                        <td colspan="3"><textarea name="descriptionD" id="descriptionD" cols="50" rows="7"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="descriptionF">Frans</label></td>
                        <td colspan="3"><textarea name="descriptionF" id="descriptionF" cols="50" rows="7"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="descriptionE">Engels</label></td>
                        <td colspan="3"><textarea name="descriptionE" id="descriptionE" cols="50" rows="7"></textarea></td>
                    </tr>
                </table>
            </fieldset>
            <fieldset>
                <legend>Images</legend>
                <table>
                    <tbody>
                        <tr>
                            <td><label for="thumbnailImage">Thumbnail</label></td>
                            <td><label for="images">Foto's</label></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="file" name="thumbnailImage">
                            </td>
                            <td colspan="3">
                                <input type="file" name="images[]" multiple>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend>Specificaties</legend>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                    </tbody>
                </table>
            </fieldset>
            <button class="btn-blue" type="submit">Submit</button>
        </form>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>