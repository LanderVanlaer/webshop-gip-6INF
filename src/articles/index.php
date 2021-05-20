<?php
    include "../includes/basicFunctions.inc.php";

    $category_id = $_GET['id'];
    $category_name = $_GET['name'] ?? '';
    
    if (empty($category_id)) redirect("/");

    include "../includes/connection.inc.php";

    $query = $con->prepare(file_get_contents("../sql/articles/articles.category.select.sql"));
    $query->bind_param('i', $category_id);
    $query->execute();
    $res = $query->get_result();

    $products = [];

    while ($row = $res->fetch_assoc()) {
        $products[] = [
                "name" => $row['name'],
                "brand" => [
                        "name" => $row['brand_name'],
                        "src" => $row['brand_logo']
                ],
                "src" => $row['path'],
                "description" => $row['description' . language(true)],
                "stars" => 5,
                "amountOfReviews" => 15,
                "price" => $row['price'],
                "link" => "/article/" . $row['id']
        ];
    };

    $query->close();
    $con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include "../resources/head.html" ?>
    <link rel="stylesheet" href="/css/articles_main.css">
    <script defer src="/js/articles/properties_menu.js"></script>
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>
        <aside>
            <form name="properties" method="GET">
                <ul>
                    <li>
                        <fieldset name="merk">
                            <legend>Merk</legend>
                            <ul>
                                <li><label><input type="checkbox" name="merk_canon"><span class="checkbox-custom"></span>Canon</label></li>
                                <li><label><input type="checkbox" name="merk_nikon"><span class="checkbox-custom"></span>Nikon</label></li>
                                <li><label><input type="checkbox" name="merk_panasonic"><span class="checkbox-custom"></span>Panasonic</label></li>
                            </ul>
                        </fieldset>
                    </li>
                    <li>
                        <fieldset name="beeldscherpte">
                            <legend>Beeldscherpte</legend>
                            <ul>
                                <li><label><input type="checkbox" name="beeldscherpte_720p"><span class="checkbox-custom"></span>720p</label></li>
                                <li><label><input type="checkbox" name="beeldscherpte_1080p"><span class="checkbox-custom"></span>1080p</label></li>
                                <li><label><input type="checkbox" name="beeldscherpte_4k"><span class="checkbox-custom"></span>4k</label></li>
                            </ul>
                        </fieldset>
                    </li>
                    <li>
                        <fieldset name="bestendigheid">
                            <legend>Bestendigheid</legend>
                            <ul>
                                <li><label><input type="checkbox" name="bestendigheid_waterdicht"><span class="checkbox-custom"></span>Waterdicht</label></li>
                                <li><label><input type="checkbox" name="bestendigheid_stofbestendig"><span class="checkbox-custom"></span>Stofbestendig</label></li>
                                <li><label><input type="checkbox" name="bestendigheid_schokbestendig"><span class="checkbox-custom"></span>Schokbestendig</label></li>
                            </ul>
                        </fieldset>
                    </li>
                </ul>
            </form>
        </aside>
        <section>
            <?php
                foreach ($products as $product) {
                    include "../resources/articles/article.php";
                }
            ?>
        </section>
    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>