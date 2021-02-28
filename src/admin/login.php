<?php
    include "../includes/validateFunctions.inc.php";
    include "../includes/basicFunctions.inc.php";
    session_start();
    
    $one_empty = $not_found = false;
    
    if (!empty($_POST)) {
        $username = var_validate($_POST["username"]);
        $password = var_validate($_POST["password"]);
        
        if (!$one_empty = is_one_empty($username, $password)) {
            include "../includes/connection.inc.php";
            
            $query = $con->prepare("SELECT * FROM `employee` WHERE username = ? LIMIT 1");
            $query->bind_param("s", $username);
            $query->execute();
            
            $res = $query->get_result();
            
            if ($row = $res->fetch_assoc()) {
                
                if (password_verify($password, $row["password"])) {
                    $_SESSION["admin"] = $row;
                    redirect("/admin/index.php");
                } else $not_found = true;
                
            } else $not_found = true;
            
            $con->close();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/admin/admin.css">
    <link rel="stylesheet" href="/css/form.css">
    <?php include "../resources/admin/head.php"; ?>
</head>

<body>
    <?php include "../resources/admin/header.php"; ?>
    <main>
        <h1>Admin login</h1>
        <form name="login" action="#" method="post">
            <?php if ($one_empty) { ?>
                <div class="message">
                    Gelieve alle verplichte velden in te vullen
                </div>
            <?php } elseif ($not_found) { ?>
                <div class="message">
                    Wachtwoord of username fout
                </div>
            <?php } ?>
            <table>
                <tr>
                    <td><label for="username">Username</label></td>
                    <td><input type="text" name="username" id="username"></td>
                </tr>
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input type="password" name="password" id="password"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn-blue" type="submit">Login</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    <?php include "../resources/admin/footer.php"; ?>
</body>

</html>