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
    
    $query = $con->prepare(file_get_contents("../sql/articles/specifications.category.select.sql"));
    $query->bind_param('i', $category_id);
    $query->execute();
    $res = $query->get_result();
    
    $specifications = [];
    $category = [];
    
    $initialized = false;
    while ($row = $res->fetch_assoc()) {
        if (!$initialized) {
            $category = [
                    'id' => $category_id,
                    'name' => $row['c_name' . language(true)]
            ];
            if (!str_compare_GET($category_name, $category['name'])) {
                $con->close();
                $query->close();
                redirect("/articles/$category_id/" . urlencode($category['name']));
            }
        }
        
        if (empty($specifications) || $specifications[array_key_last($specifications)]['id'] != $row['specification_id'])
            $specifications[] = [
                    'id' => $row['specification_id'],
                    'name' => $row['s_name' . language(true)],
                    'values' => []
            ];
        
        $specifications[array_key_last($specifications)]["values"][] = $row['value'];
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
    <style>
        main > h1 {
            font-size: 5rem;
        }
    </style>
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>
        <h1><?= $category["name"] ?></h1>
        <aside>
            <form name="properties" method="GET">
                <ul>
                    <?php
                        foreach ($specifications as $specification) {
                            ?>
                            <li>
                                <fieldset name="<?= $specification["id"] ?>">
                                    <legend><?= $specification["name"] ?></legend>
                                    <ul>
                                        <?php
                                            foreach ($specification['values'] as $value) {
                                                ?>
                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="<?= urlencode("{$specification["id"]}_$value") ?>">
                                                        <span class="checkbox-custom"></span>
                                                        <?= $value ?>
                                                    </label>
                                                </li>
                                                <?php
                                            }
                                        ?>
                                    </ul>
                                </fieldset>
                            </li>
                            <?php
                        }
                    ?>
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