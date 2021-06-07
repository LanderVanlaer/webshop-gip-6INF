<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    print_r($_POST);

    $article_id = var_validate($_POST['article-id']);
    $name = str_replace("/", "", var_validate($_POST["name"]));
    $price = var_validate($_POST['price']);
    $descriptionD = var_validate($_POST['descriptionD']);
    $descriptionF = var_validate($_POST['descriptionF']);
    $descriptionE = var_validate($_POST['descriptionE']);

    if (is_one_empty($article_id, $name, $price, $descriptionD, $descriptionE, $descriptionF))
        redirect(".?article-id=$article_id&err=" . Error::empty_value);

    if (!is_numeric($article_id) || !is_numeric($price))
        redirect(".?article-id=$article_id&err=" . Error::bad_request);


    include_once "../../../includes/connection.inc.php";

    $query = $con->prepare("UPDATE article SET name = ?, price = ?, descriptionD = ?, descriptionF = ?, descriptionE = ? WHERE id = ?");
    $query->bind_param('sdsssi', $name, $price, $descriptionD, $descriptionF, $descriptionE, $article_id);

    $succes = $query->execute();
    $affected_rows = $query->affected_rows;

    $query->close();
    $con->close();

    if (!$succes)
        redirect(".?article-id=$article_id&err=" . Error::internal);

    if ($affected_rows <= 0)
        redirect(".?article-id=$article_id&err=" . Error::not_modified);

    redirect(".?article-id=$article_id");