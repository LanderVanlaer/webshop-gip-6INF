<?php

    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";
    include "../../../includes/fileFuncties.inc.php";

    $error = 0;
    $executed = $succes = $last_id = false;

    if (!empty($_POST)) {
        $name = var_validate($_POST["name"]);
        $brandId = var_validate($_POST["brand_id"]);
        $price = var_validate($_POST["price"]);
        
        $descriptionD = var_validate($_POST["descriptionD"]);
        $descriptionF = var_validate($_POST["descriptionF"]);
        $descriptionE = var_validate($_POST["descriptionE"]);
        
        $categories = array();
        if (!empty($_POST["categories"]))
            foreach ($_POST["categories"] as $cat) $categories[] = intval($cat);
        
        $specs = preg_grep_keys("/^specification_\d+$/i", $_POST);
        
        $thumbnailImage = $_FILES['thumbnailImage'];
        $images = format_file_array($_FILES['images']);
        
        if (is_one_empty($name, $brandId, $price, $descriptionD, $descriptionF, $descriptionE, $categories, $specs, $thumbnailImage, $images)) {
            $error = Error::empty_value;
            goto end;
        }

        //nakijken of de gegeven images weldeglijk images zijn
        $are_images = is_image($thumbnailImage) && $thumbnailImage['error'] == 0;

        for ($i = 0; $i < count($images) && $are_images; $i++)
            if (!is_image($images[$i]) || $thumbnailImage['error'] != 0)
                $are_images = false;

        if (!$are_images) {
            $error = Error::file_not_allowed;
            goto end;
        }

        //nakijken of de gegeven images niet te groot zijn
        $bytes = 2500000; //2.5 MB
        $images_file_size_ok = file_size_less($thumbnailImage, $bytes);

        for ($i = 0; $i < count($images) && $images_file_size_ok; $i++)
            if (!file_size_less($images[$i], $bytes))
                $images_file_size_ok = false;

        if (!$images_file_size_ok) {
            $error = Error::file_too_large;
            goto end;
        }

        include "../../../includes/connection.inc.php";

        //Check if not duplicate
        $query = $con->prepare("SELECT id FROM `article` WHERE name = ? LIMIT 1");
        $query->bind_param('s', $name);
        $query->execute();
        $res = $query->get_result();

        if ($res->num_rows > 0) {
            //duplicate
            $last_id = $res->fetch_assoc()['id'];
            $error = Error::duplicate;
            goto end;
        }

        $query->close();

        $query = $con->prepare(file_get_contents("../../../sql/admin/add/article/add.sql"));
        $query->bind_param('issssd', $brandId, $name, $descriptionD, $descriptionF, $descriptionE, $price);
        $query->execute();
        $last_id = $query->insert_id;
        $query->close();

        //save images
        $query = $con->prepare(file_get_contents("../../../sql/admin/add/article/articleImage-add.sql"));

        $newFileName = file_save($thumbnailImage, "../../../images/articles");
        $num = 1;
        $query->bind_param('sii', $newFileName, $last_id, $num);
        $query->execute();

        $num = 0;
        foreach ($images as $image) {
            $newFileName = file_save($image, "../../../images/articles");
            $query->bind_param('sii', $newFileName, $last_id, $num);
            $query->execute();
        }
        $query->close();

        //save categories
        $query = $con->prepare(file_get_contents("../../../sql/admin/add/article/articleCategory-add.sql"));

        foreach ($categories as $cat) {
            $query->bind_param('ii', $cat, $last_id);
            $query->execute();
        }
        $query->close();

        //save specifications
        $query = $con->prepare(file_get_contents("../../../sql/admin/add/article/articleSpecification-add.sql"));

        $executed = true;

        foreach ($specs as $specKey => $specValue) {
            preg_match('/^specification_(\d+)$/i', $specKey, $match);
            $specId = $match[1];
            $query->bind_param('isi', $last_id, $specValue, $specId);
            $query->execute();
        }
        $succes = true;

        $query->close();
        $con->close();
    }
    end:
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
    <link rel="stylesheet" href="/css/admin/add/article.css">
    <script src="/js/admin/add/article-category-dynamic.js" defer></script>
    <script src="/js/admin/add/article-brand-dynamic.js" defer></script>
    <title>Admin - Add article</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Toevoegen artikel</h1>
        <form action="#" method="post" enctype="multipart/form-data">
            <?php if ($error || $executed): ?>
                <div class="message">
                    <?php switch ($error) {
                        case Error::empty_value:
                            echo "Gelieve alle verplichte velden in te vullen";
                            break;
                        case Error::file_not_allowed:
                            echo "U kan geen bestanden uploaden van dit type";
                            break;
                        case Error::file_too_large:
                            echo "Het bestand is te groot";
                            break;
                        case Error::duplicate:
                            echo "Er bestaat al een artikel met deze naam, id: $last_id";
                            break;
                    }
                        if ($executed) {
                            if ($succes)
                                echo "Merk toegevoegd met id: $last_id";
                            else
                                echo "Er is iets misgelopen";
                        }
                    ?>
                </div>
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
                            <td id="brand-dynamic">
                                <input type="text" name="brand" id="brand" autocomplete="off">
                                <input class="none" type="number" name="brand_id">
                                <div class="data"></div>
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
                <legend>Categorieën</legend>
                <div class="split">
                    <table>
                        <thead>
                            <tr>
                                <th>Toegevoegd</th>
                            </tr>
                        </thead>
                        <tbody id="categoryBody">
                        </tbody>
                    </table>
                    <table>
                        <thead>
                            <tr>
                                <th colspan="4">Zoeken</th>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <input type="search" name="categorySearch" id="categorySearch">
                                </td>
                            </tr>
                        </thead>
                        <tbody id="categoryBodySearch">
                        </tbody>
                    </table>
                </div>
            </fieldset>
            <fieldset>
                <legend>Specificaties</legend>
                <table>
                    <thead>
                        <tr>
                            <th colspan="3">Naam</th>
                            <th>Waarde</th>
                        </tr>
                    </thead>
                    <tbody id="specificationBody">
                    </tbody>
                </table>
            </fieldset>
            <button class="btn-blue" type="submit">Submit</button>
        </form>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>