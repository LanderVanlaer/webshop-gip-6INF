<?php
    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/basicFunctions.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    if (empty($_GET['id']) || !is_numeric($_GET['id']))
        redirect("/admin/articles");

    include_once "../../../includes/connection.inc.php";

    $article_id = intval(var_validate($_GET['id']));

    $article = array();

    $query = $con->prepare(file_get_contents("../../../sql/article/article.select.sql"));
    $query->bind_param('i', $article_id);
    $query->execute();
    $res = $query->get_result();

    $row = $res->fetch_assoc();
    $query->close();

    if (empty($row))
        redirect('/admin/articles');

    $article['name'] = $row['name'];
    $article['id'] = $article_id;
    $article['price'] = $row['price'];
    $article['link'] = "/article/$article_id";
    $article['brand'] = [
            "name" => $row['brand_name'],
            "id" => $row['brand_id'],
            "src" => "/images/brands/{$row['logo']}",
    ];
    $article['description'] = [
            'd' => $row['descriptiond'],
            'f' => $row['descriptionf'],
            'e' => $row['descriptione'],
    ];

    $article['img'] = array();

    $query = $con->prepare(file_get_contents("../../../sql/article/articleImage.select.sql"));
    $query->bind_param('i', $article_id);
    $query->execute();
    $res = $query->get_result();
    while ($row = $res->fetch_assoc()) {
        $article['img'][] = [
                'id' => $row['id'],
                'link' => "/images/articles/{$row['path']}",
                'name' => $row['path']
        ];
    }
    $query->close();


    $article['category'] = array();

    $query = $con->prepare(file_get_contents("../../../sql/article/articleSpecification.select.sql"));
    $query->bind_param('i', $article_id);
    $query->execute();
    $res = $query->get_result();
    $last_cat_id = -1;
    while ($row = $res->fetch_assoc()) {
        if ($last_cat_id != $row['category_id']) {
            $last_cat_id = $row['category_id'];
            $article['category'][] = [
                    'name' => [
                            'd' => $row['category_name_d'],
                            'f' => $row['category_name_f'],
                            'e' => $row['category_name_e'],
                    ],
                    'specs' => []];
        }
        $article['category'][array_key_last($article['category'])]['specs'][] = [
                'value' => $row['value'],
                'key' => [
                        'd' => $row['specification_name_d'],
                        'f' => $row['specification_name_f'],
                        'e' => $row['specification_name_e'],
                ]
        ];
    }
    $query->close();
    $con->close();
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../../resources/admin/head.php"; ?>
    <link rel="stylesheet" href="/css/admin/table.css">
    <title>Admin - Article</title>
    <link rel="stylesheet" href="/css/admin/articles/get/style.css">
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Article: <span><?= $article['id'] ?></span></h1>
        <div>
            <a href="/admin/update/article?article-id=<?= $article['id'] ?>" class="btn-blue">Update</a>
        </div>
        <div class="tables">
            <div class="split">
                <div>
                    <table>
                        <caption>Article</caption>
                        <tbody>
                            <tr>
                                <th>Id</th>
                                <td><?= $article['id'] ?></td>
                            </tr>
                            <tr>
                                <th>Naam</th>
                                <td><?= $article['name'] ?></td>
                            </tr>
                            <tr>
                                <th class="center" colspan="2">Beschrijvingen</th>
                            </tr>
                            <tr>
                                <th>Nederlands</th>
                                <td><?= $article['description']['d'] ?></td>
                            </tr>
                            <tr>
                                <th>Frans</th>
                                <td><?= $article['description']['f'] ?></td>
                            </tr>
                            <tr>
                                <th>Engels</th>
                                <td><?= $article['description']['e'] ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table>
                        <caption>Merk</caption>
                        <tbody>
                            <tr>
                                <th>Id</th>
                                <td><?= $article['brand']['id'] ?></td>
                            </tr>
                            <tr>
                                <th>Naam</th>
                                <td><?= $article['brand']['name'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div>
                    <table id="images">
                        <caption>Images</caption>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Naam</th>
                                <th>Afbeelding</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($article['img'] as $img): ?>
                                <tr>
                                    <td><?= $img['id'] ?></td>
                                    <td><?= $img['name'] ?></td>
                                    <td class="center image">
                                        <img src="<?= $img['link'] ?>" alt="<?= $article['name'] ?>, <?= $img['id'] ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <table>
                    <caption>Categorieën</caption>
                    <thead>
                        <tr>
                            <th colspan="3">Naam</th>
                            <th colspan="4">Specificatie</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Nederlands</th>
                            <th rowspan="2">Frans</th>
                            <th rowspan="2">Engels</th>
                            <th colspan="3">Naam</th>
                            <th rowspan="2">Value</th>
                        </tr>
                        <tr>
                            <th>Nederlands</th>
                            <th>Frans</th>
                            <th>Engels</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($article['category'] as $category): ?>
                            <tr>
                                <td class="center" rowspan="<?= count($category['specs']) + 1 ?>"><?= $category['name']['d'] ?></td>
                                <td class="center" rowspan="<?= count($category['specs']) + 1 ?>"><?= $category['name']['f'] ?></td>
                                <td class="center" rowspan="<?= count($category['specs']) + 1 ?>"><?= $category['name']['e'] ?></td>
                                <td class="empty" colspan="4"></td>
                            </tr>
                            <?php foreach ($category['specs'] as $spec): ?>
                                <tr class="color-odd">
                                    <td><?= $spec['key']['d'] ?></td>
                                    <td><?= $spec['key']['f'] ?></td>
                                    <td><?= $spec['key']['e'] ?></td>
                                    <td><?= $spec['value'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>