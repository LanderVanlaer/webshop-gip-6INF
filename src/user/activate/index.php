<?php
    include_once "../../includes/basicFunctions.inc.php";
    include_once "../../includes/validateFunctions.inc.php";

    if (empty($_GET["code"]))
        goto end;

    $code = var_validate($_GET["code"]);

    include_once "../../includes/connection.inc.php";

    $query = $con->prepare("UPDATE customer SET active = 1, registration_code = '' WHERE registration_code = ? LIMIT 1;");
    $query->bind_param("s", $code);
    $query->execute();

    if ($query->affected_rows)
        redirect("/user/login");
    end:
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error</title>
</head>
<body>
    Something went wrong, you will be redirected in 10 seconds.
    Or click <a href="/">here</a>.
    <script>
        setInterval(() => window.location = "/", 10000);
    </script>
</body>
</html>
