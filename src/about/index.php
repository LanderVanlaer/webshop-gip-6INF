<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
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
        <h1>About</h1>
        <p>Click <a href="/Ondernemingsplan.pdf" target="_blank">HERE</a> to get the PDF!</p>
        <object data="/Ondernemingsplan.pdf" type="application/pdf">
            <p>Click <a href="/Ondernemingsplan.pdf" target="_blank">HERE</a> to get the PDF!</p>
        </object>
        <section class="mission-vision">
            <section class="mission">
                <h3>Mission</h3>
                <p>
                    Het helpen en het beste bieden voor de foto’s van de professionele en hobbyfotograaf.
                </p>
            </section>
            <section class="vision">
                <h3>Vission</h3>
                <p>
                    Wij willen zo veel mogelijk kwalitatieve foto geassocieerde producten verkopen, zodat elke fotograaf met zijn/haar budget de benodigde producten vindt
                    op één gezamenlijke plek.
                </p>
            </section>
        </section>
    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>