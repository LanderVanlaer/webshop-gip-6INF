<?php
    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";
    
    $id = empty($_GET['article-id']) ? "" : var_validate($_GET['article-id']);
    $article = [];
    
    if (!empty($id)) {
        include "../../../includes/connection.inc.php";
        $query = $con->prepare(file_get_contents("../../../sql/admin/update/article/article-brand.article.select.sql"));
        $query->bind_param("i", $id);
        $query->execute();
        $res = $query->get_result();
        $row = $res->fetch_assoc();
        $query->close();
        
        if ($res->num_rows <= 0)
            goto end;
        
        
        $article = [
                'id' => $row['id'],
                'name' => $row['name'],
                'descriptionD' => $row['descriptionD'],
                'descriptionE' => $row['descriptionE'],
                'descriptionF' => $row['descriptionF'],
                'price' => $row['price'],
                'brand' => [
                        'id' => $row['brand_id'],
                        'name' => $row['brand_name'],
                ],
                'categories' => [],
                'specifications' => [],
                'images' => []
        ];
        
        $query = $con->prepare(file_get_contents("../../../sql/admin/update/article/category.article.select.sql"));
        $query->bind_param("i", $id);
        $query->execute();
        $res = $query->get_result();
        
        if ($res->num_rows <= 0) {
            $query->close();
            goto end;
        }
        
        while ($row = $res->fetch_assoc()) {
            $article['categories'][] = [
                    'id' => $row['id'],
                    'nameD' => $row['nameD'],
                    'nameE' => $row['nameE'],
                    'nameF' => $row['nameF'],
            ];
        }
        
        $query->close();
        
        $query = $con->prepare(file_get_contents("../../../sql/admin/update/article/articlespecification.article.select.sql"));
        $query->bind_param("i", $id);
        $query->execute();
        $res = $query->get_result();
        
        
        while ($row = $res->fetch_assoc()) {
            $article['specifications'][] = [
                    'articlespecification_id' => $row['articlespecification_id'],
                    'value' => $row['value'],
                    'nameD' => $row['nameD'],
                    'nameF' => $row['nameF'],
                    'nameE' => $row['nameE'],
            ];
        }
        
        $query->close();
        
        
        $query = $con->prepare(file_get_contents("../../../sql/admin/update/article/articleimage.article.select.sql"));
        $query->bind_param("i", $id);
        $query->execute();
        $res = $query->get_result();
        
        
        while ($row = $res->fetch_assoc()) {
            $article['images'][] = [
                    'id' => $row['id'],
                    'path' => $row['path'],
            ];
        }
        
        $query->close();
        
        end:
        $con->close();
    }

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Article</title>
    <?php include "../../../resources/admin/head.php"; ?>
    <link rel="stylesheet" href="/css/form.css">
    <style>
        #images img {
            max-width: 7.5rem;
            max-height: 5rem;
        }

        #images > div {
            display: inline-block;
            width: 8rem;
            height: 5rem;
        }
    </style>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Update Article</h1>
        <form action="#" method="get">
            <fieldset>
                <table class="margin-center">
                    <tr>
                        <td><label class="required" for="article-id">Id:</label></td>
                        <td><input type="number" name="article-id" id="article-id" required value="<?= $id ?>" min="0"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button class="btn-blue" type="submit">Update</button>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <?php if (!empty($article) && count($article)): ?>
            <form action="name-price.php" method="post">
                <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
                <fieldset>
                    <legend>Basic</legend>
                    <table>
                        <tbody>
                            <tr>
                                <td><label for="name">Naam:</label></td>
                                <td><input type="text" name="name" id="name" maxlength="32" required value="<?= $article['name'] ?>"></td>
                            </tr>
                            <tr>
                                <td><label for="price">Prijs:</label></td>
                                <td><input type="number" min="1" name="price" id="price" required value="<?= $article['price'] ?>"></td>
                            </tr>
                            <tr>
                                <td><label for="descriptionD">Beschrijving Nederlands:</label></td>
                                <td><textarea name="descriptionD" id="descriptionD" cols="75" rows="7" required><?= $article['descriptionD'] ?></textarea></td>
                            </tr>
                            <tr>
                                <td><label for="descriptionF">Beschrijving Frans:</label></td>
                                <td><textarea name="descriptionF" id="descriptionF" cols="75" rows="7" required><?= $article['descriptionF'] ?></textarea></td>
                            </tr>
                            <tr>
                                <td><label for="descriptionE">Beschrijving Engels:</label></td>
                                <td><textarea name="descriptionE" id="descriptionE" cols="75" rows="7" required><?= $article['descriptionE'] ?></textarea></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <button class="btn-blue" type="submit">Wijzig</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </fieldset>
            </form>
            <form action="images-remove.php" method="post">
                <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
                <fieldset>
                    <legend>Images verwijderen</legend>
                    <div id="images">
                        <?php foreach ($article['images'] as $image): ?>
                            <div>
                                <div>
                                    <a href="/images/articles/<?= $image['path'] ?>" target="_blank">
                                        <img src="/images/articles/<?= $image['path'] ?>" alt="<?= $image['id'] ?>">
                                    </a>
                                </div>
                                <div>
                                    <label>
                                        <input type="checkbox" name="images[<?= $image['id'] ?>]" id="images[<?= $image['id'] ?>]">
                                        <span class="checkbox-custom"></span>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="btn-blue" type="submit">Wijzig</button>
                </fieldset>
            </form>
            <form action="images-add.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
                <fieldset>
                    <legend>Images Toevoegen</legend>
                    <input type="file" name="files[]" id="files[]" multiple>
                    <button class="btn-blue" type="submit">Wijzig</button>
                </fieldset>
            </form>
            <form action="category-remove.php" method="post">
                <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
                <fieldset>
                    <legend>Categorieën Verwijderen</legend>
                    <table>
                        <tbody>
                            <?php foreach ($article['categories'] as $category): ?>
                                <tr>
                                    <td><label for="categories[<?= $category['id'] ?>]"><?= $category['nameD'] ?></label></td>
                                    <td><label for="categories[<?= $category['id'] ?>]"><?= $category['nameE'] ?></label></td>
                                    <td><label for="categories[<?= $category['id'] ?>]"><?= $category['nameF'] ?></label></td>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="categories[<?= $category['id'] ?>]" id="categories[<?= $category['id'] ?>]">
                                            <span class="checkbox-custom"></span>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="center">
                                    <button class="btn-blue" type="submit">Wijzig</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </fieldset>
            </form>
            <form action="specification.php" method="post">
                <input type="hidden" name="article-id" value="<?= $article['id'] ?>">
                <table>
                    <tbody>
                        <?php foreach ($article['specifications'] as $specification): ?>
                            <tr>
                                <td><label for="specification<?= $specification['articlespecification_id'] ?>"><?= $specification['nameD'] ?></label></td>
                                <td><label for="specification<?= $specification['articlespecification_id'] ?>"><?= $specification['nameE'] ?></label></td>
                                <td><label for="specification<?= $specification['articlespecification_id'] ?>"><?= $specification['nameF'] ?></label></td>
                                <td><input type="text" name="specification<?= $specification['articlespecification_id'] ?>"
                                           id="specification<?= $specification['articlespecification_id'] ?>" value="<?= $specification['value'] ?>"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="center">
                                <button class="btn-blue" type="submit">Wijzig</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
        <?php endif; ?>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>
