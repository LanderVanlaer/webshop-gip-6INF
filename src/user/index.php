<?php
    include_once "../includes/Error.php";
    
    use includes\Error as Error;
    
    include "../includes/user/userFunctions.inc.php";
    session_start();
    $logged_in = isLoggedIn();

    $historyArticles = [];
    $likedArticles = [];

    if (isLoggedIn()) {
        include "../includes/connection.inc.php";
        $query = $con->prepare(file_get_contents("../sql/customer/visited.customer.select.sql"));
        $user_id = $_SESSION['user']['id'];
        $query->bind_param('i', $user_id);
        $query->execute();

        $res = $query->get_result();
        while ($row = $res->fetch_assoc()) {
            $historyArticles[] = [
                    'id' => $row['article_id'],
                    'name' => $row['article_name'],
                    'path' => $row['img_path']
            ];
        }
        $query->close();

        $query = $con->prepare(file_get_contents("../sql/customer/like.customer.select.sql"));
        $query->bind_param('i', $user_id);
        $query->execute();

        $res = $query->get_result();
        while ($row = $res->fetch_assoc()) {
            $likedArticles[] = [
                    'id' => $row['article_id'],
                    'name' => $row['article_name'],
                    'path' => $row['img_path']
            ];
        }
        $query->close();

        $con->close();
    }
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include "../resources/head.html" ?>
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/user/style.css">
</head>

<body>
    <?php include "../resources/header.php" ?>
    <main>
        <h1>Profiel</h1>
        <?php if ($logged_in): ?>
            <a class="btn-blue" href="logout">Afmelden</a>
            <div class="user-welcome-message">
                <h3>Hallo <?= $_SESSION['user']['firstname'] ?></h3>
            </div>
            <form action="/user/changePassword/" method="POST">
                <?php if (isset($_GET['error'])) Error::print_admin_message($_GET['error']); ?>
                <fieldset>
                    <legend>Verander Wachtwoord</legend>
                    <table>
                        <tr>
                            <td><label for="password">Oude Wachtwoord</label></td>
                            <td><input type="password" id="password" name="password"></td>
                        </tr>
                        <tr>
                            <td><label for="password_new">Nieuwe wachtwoord</label></td>
                            <td><input type="password" id="password_new" name="password_new"></td>
                        </tr>
                        <tr>
                            <td><label for="password_new_confirm">Bevestiging</label></td>
                            <td><input type="password" id="password_new_confirm" name="password_new_confirm"></td>
                        </tr>
                    </table>
                    <button class="btn-blue" type="submit">Verander</button>
                </fieldset>
            </form>
            <section class="liked">
                <h2>Verlanglijstje</h2>
                <div class="split">
                    <?php foreach ($likedArticles as $article) : ?>
                        <article>
                            <a href="/article/<?= $article['id'] ?>">
                                <img class="product-name-logo margin-center" src="/images/articles/<?= $article['path'] ?>" alt="">
                                <span class="product-name"><?= $article['name'] ?></span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <section class="history">
                <h2>History</h2>
                <div class="split">
                    <?php foreach ($historyArticles as $article) : ?>
                        <article>
                            <a href="/article/<?= $article['id'] ?>">
                                <img class="product-name-logo margin-center" src="/images/articles/<?= $article['path'] ?>" alt="">
                                <span class="product-name"><?= $article['name'] ?></span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <a class="btn-blue" href="register">Registreren</a>
            <a class="btn-blue" href="login">Aanmelden</a>
        <?php endif; ?>
    </main>
    <?php include "../resources/footer.php" ?>
</body>

</html>