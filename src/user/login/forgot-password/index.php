<?php
    session_start();
    include_once "../../../includes/Error.php";
    
    use includes\Error as Error;

    include_once "../../../includes/Mail.php";
    include_once "../../../includes/basicFunctions.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";
    include_once "../../../includes/user/userFunctions.inc.php";
    
    if (isLoggedIn())
        redirect("/user");
    
    $error = 0;
    $reset = false;
    $email = "";

    if (!empty($_POST)) {
        $email = var_validate($_POST["email"]);
        
        if (is_one_empty($email)) {
            $error = Error::empty_value;
            goto end;
        }
        
        if (!email_possible($email)) {
            $error = Error::email_not_valid;
            goto end;
        }

        include_once "../../../includes/connection.inc.php";
        $query = $con->prepare("SELECT id, registration_code, firstname, lastname, active FROM customer WHERE email = ? LIMIT 1");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $query->close();
        
        if ($result->num_rows <= 0 || !$row) {
            $con->close();
            $error = Error::customer_not_found_by_email;
            goto end;
        }

        if ($row["active"] != 1) {
            $con->close();
            $error = Error::customer_not_active;
            goto end;
        }

        $registration_code = $row["registration_code"];

        if (empty($registration_code)) {
            $registration_code = uniqid();
            $query = $con->prepare("UPDATE customer SET registration_code = ? WHERE id = ?");
            $query->bind_param("si", $registration_code, $row['id']);
            $query->execute();
            $query->close();
        }


        $reset = true;

        Mail\mail_send_password_recovery($email, $row["firstname"], $row["lastname"], $registration_code);

        $con->close();
    }
    end:
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _['title'] ?></title>
    <?php include "../../../resources/head.html" ?>
    <link rel="stylesheet" href="/css/form.css">
</head>

<body>
    <?php include "../../../resources/header.php" ?>
    <main>
        <h1><?= _['title'] ?></h1>
        <form action="#" method="post">
            <?php
                Error::print_admin_message($error);
                if ($reset) {
                    Error::print_message(_['email_send']);
                }
            ?>
            <table class="margin-center responsive">
                <tr>
                    <td><label class="required" for="email"><?= _['email'] ?>: </label></td>
                    <td><input id="email" name="email" required type="email" value="<?= $email ?>"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button class="btn-blue" type="submit"><?= _['send'] ?></button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    <?php include "../../../resources/footer.php" ?>
</body>

</html>