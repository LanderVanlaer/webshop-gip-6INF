<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/articles_main.css">
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
                $product = array(
                    "name" => "DC-FZ1000 II",
                    "brand" => array("name" => "LUMIX", "src" => "/images/brands/panasonic.svg"),
                    "src" => "/images/products/01_FZ1000M2_thumbnail.png",
                    "description" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,  quis ...",
                    "stars" => 3,
                    "amountOfReviews" => 100,
                    "price" => 1599.99,
                    "link" => "#"
                );
                include "../resources/articles/article.php" ?>
        </section>
    </main>
    <?php include "../resources/footer.php" ?>
    <script src="/js/header.js"></script>
    <script src="/js/articles/properties_menu.js"></script>
</body>

</html>