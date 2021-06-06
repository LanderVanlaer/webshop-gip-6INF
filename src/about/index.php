<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
    <?php include "../resources/head.html" ?>
    <style>
        main {
            padding-left: 0;
            padding-right: 0;
        }

        h1, section {
            padding: 1rem;
        }

        object[type="application/pdf"] {
            display: block;
            width: 100%;
            height: 50vw;
        }
    </style>
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>
        <h1><?= _['title'] ?></h1>
        <p>Click <a href="/Ondernemingsplan.pdf" target="_blank">HERE</a> to get the PDF!</p>
        <object data="/Ondernemingsplan.pdf" type="application/pdf">
            <p>Click <a href="/Ondernemingsplan.pdf" target="_blank">HERE</a> to get the PDF!</p>
        </object>
        <section class="mission-vision">
            <section class="mission">
                <h3><?= _['mission_title'] ?></h3>
                <p>
                    <?= _['mission_text'] ?>
                </p>
            </section>
            <section class="vision">
                <h3><?= _['vision_title'] ?></h3>
                <p>
                    <?= _['vision_text'] ?>
                </p>
            </section>
        </section>
    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>