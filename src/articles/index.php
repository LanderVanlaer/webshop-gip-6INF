<?php
    include_once "../includes/basicFunctions.inc.php";

    $category_id = $_GET['id'];
    $category_name = $_GET['name'] ?? '';
    
    if (empty($category_id)) redirect("/");
    
    include_once "../includes/connection.inc.php";
    
    $query = $con->prepare(file_get_contents("../sql/articles/articles.category.select.sql"));
    $query->bind_param('i', $category_id);
    $query->execute();
    $res = $query->get_result();

    $products = [];

    $brands = [];

    $query_product_specification = $con->prepare(file_get_contents("../sql/articles/article_specifications.article-category.select.sql"));
    $product_id = 0;
    $query_product_specification->bind_param('ii', $product_id, $category_id);

    while ($row = $res->fetch_assoc()) {
        if (!in_array($row['brand_name'], $brands)) {
            $brands[] = $row['brand_name'];
        }

        $product_specifications = [
                -1 => $row['brand_name']
        ];

        $product_id = $row['id'];
        $query_product_specification->execute();
        $product_spec_res = $query_product_specification->get_result();
        while ($product_spec = $product_spec_res->fetch_assoc()) {
            $product_specifications[$product_spec['specification_id']] = $product_spec['value'];
        }

        $products[] = [
                "name" => $row['name'],
                "id" => $row['id'],
                "brand" => [
                        "name" => $row['brand_name'],
                        "src" => $row['brand_logo']
                ],
                "src" => $row['path'],
                "description" => $row['description' . language(true)],
                "price" => $row['price'],
                "link" => "/article/" . $row['id'],
                'specifications' => $product_specifications
        ];
    }

    sort($brands);

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
    }
    
    $query->close();
    $con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category - <?= $category["name"] ?></title>
    <?php include "../resources/head.html" ?>
    <link rel="stylesheet" href="/css/articles_main.css">
    <script defer src="/js/articles/properties_menu.js"></script>
    <style>
        main > h1 {
            font-size: 5rem;
        }
    </style>
    <script>
        addEventListener('load', () => {
            const main = document.querySelector('main');
            document.querySelector('#show-filter').addEventListener('click', () => main.classList.toggle('show-filters'));
        });
    </script>
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>
        <h1><?= $category["name"] ?></h1>
        <div class="show-filters-div-btn">
            <button class="btn-blue" id="show-filter"><?= _['show_filters'] ?></button>
        </div>
        <aside>
            <form name="properties" method="GET">
                <ul>
                    <!-- BRAND-->
                    <li>
                        <fieldset name="brand">
                            <legend><?= _['brand'] ?></legend>
                            <ul>
                                <?php foreach ($brands as $brand) {
                                    ?>
                                    <li>
                                        <label>
                                            <input type="checkbox" name="<?= urlencode("-1_$brand") ?> ">
                                            <span class="checkbox-custom"></span>
                                            <?= $brand ?>
                                        </label>
                                    </li>
                                    <?php
                                } ?>
                            </ul>
                        </fieldset>
                    </li>

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
                                                        <?php
                                                            echo yes_no_to_unicode($value);
                                                        ?>
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