<?php
    include_once "../../../includes/Error.php";
    
    use includes\Error as Error;
    
    include_once "../../../includes/admin/admin.inc.php";
    
    include_once "../../../includes/connection.inc.php";
    
    $error = 0;
    $executed = $succes = false;
    
    if (!empty($_POST)) {
        include_once "../../../includes/validateFunctions.inc.php";
        
        $current_password = empty($_POST['current-password']) ? '' : var_validate($_POST['current-password']);
        $new_password = empty($_POST['new-password']) ? '' : var_validate($_POST['new-password']);
        $new_password_confirm = empty($_POST['new-password-confirm']) ? '' : var_validate($_POST['new-password-confirm']);
        $username = empty($_POST['username']) ? '' : var_validate($_POST['username']);
        $firstname = empty($_POST['firstname']) ? '' : var_validate($_POST['firstname']);
        $lastname = empty($_POST['lastname']) ? '' : var_validate($_POST['lastname']);
        
        $info_form_empty = is_one_empty($username, $firstname, $lastname);
        $password_form_empty = is_one_empty($current_password, $new_password, $new_password_confirm);
        
        if ($info_form_empty && $password_form_empty) {
            $error = Error::empty_value;
            goto end;
        }
        
        
        if ($info_form_empty)
            goto next;
        
        
        $query = $con->prepare("UPDATE employee set firstname = ?, lastname = ?, username = ? WHERE id = ?;");
        $query->bind_param('sssi', $firstname, $lastname, $username, $_SESSION["admin"]["id"]);
        $query->execute();
        $query->close();
        
        
        next:
        if ($password_form_empty)
            goto end;
        
        if (strcmp($new_password, $new_password_confirm)) {
            $error = Error::password_not_match;
            goto end;
        }
        if (count(password_not_possible($new_password))) {
            $error = Error::password_not_possible;
            goto end;
        }
        
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $query = $con->prepare("SELECT password FROM employee WHERE id = ?");
        $query->bind_param('i', $_SESSION["admin"]["id"]);
        $query->execute();
        
        if (!password_verify($current_password, $query->get_result()->fetch_assoc()['password'])) {
            $error = Error::login_fail;
            goto end;
        }
        $query->close();
        

        $executed = true;
        $query = $con->prepare("UPDATE employee set password = ? WHERE id = ?");
        $query->bind_param('si', $new_password_hash, $_SESSION["admin"]["id"]);
        
        $succes = $query->execute();
        $query->close();
    }
    end:
    
    $query = $con->prepare("SELECT password, username, firstname, lastname FROM employee WHERE id = ?;");
    $query->bind_param('i', $_SESSION["admin"]["id"]);
    $query->execute();
    $res = $query->get_result();
    $row = $res->fetch_assoc();
    $query->close();
    
    $employee = [
            'id' => $_SESSION['admin']['id'],
            'username' => $row['username'],
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
    ];
?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include "../../../resources/admin/head.html"; ?>
    <link rel="stylesheet" href="/css/form.css">
    <link rel="stylesheet" href="/css/admin/table.css">
    <title>Admin - Update Employee</title>
</head>
<body>
    <?php include "../../../resources/admin/header.php"; ?>
    <main>
        <h1>Update Employee</h1>
        <form action="#" method="post">
            <?php
                Error::print_admin_message($error, $executed, $succes);
            ?>
            <fieldset>
                <legend>Info</legend>
                <table>
                    <tbody>
                        <tr>
                            <th><label for="id">Id</label></th>
                            <td><input required disabled type="number" id="id" value="<?= $employee['id'] ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="username">Username</label></th>
                            <td><input required type="text" name="username" id="username" value="<?= $employee['username'] ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="firstname">Firstname</label></th>
                            <td><input required type="text" name="firstname" id="firstname" value="<?= $employee['firstname'] ?>"></td>
                        </tr>
                        <tr>
                            <th><label for="lastname">Lastname</label></th>
                            <td><input required type="text" name="lastname" id="lastname" value="<?= $employee['lastname'] ?>"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="btn-blue" type="submit">Submit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </form>
        <form action="#" method="post">
            <fieldset>
                <legend>Wachtwoord</legend>
                <table>
                    <tbody>
                        <tr>
                            <th><label for="current-password">Huidig wachtwoord</label></th>
                            <td><input required type="password" name="current-password" id="current-password"></td>
                        </tr>
                        <tr>
                            <th><label for="new-password">Nieuw wachtwoord</label></th>
                            <td><input required type="password" name="new-password" id="new-password"></td>
                        </tr>
                        <tr>
                            <th><label for="new-password-confirm">Bevestig nieuw wachtwoord</label></th>
                            <td><input required type="password" name="new-password-confirm" id="new-password-confirm"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="btn-blue" type="submit">Submit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </form>
    </main>
    <?php include "../../../resources/admin/footer.php"; ?>
</body>
</html>