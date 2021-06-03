<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    $article_id = var_validate($_POST['article-id']);
    $visible = !empty($_POST['visible']);

    if (is_one_empty($article_id))
        redirect(".?article-id=$article_id&err=" . Error::empty_value);

    if (!is_numeric($article_id))
        redirect(".?article-id=$article_id&err=" . Error::bad_request);


    include_once "../../../includes/connection.inc.php";

    $query = $con->prepare("UPDATE article SET visible = ? WHERE id = ?");
    $query->bind_param('ii', $visible, $article_id);

    $succes = $query->execute();
    $affected_rows = $query->affected_rows;

    $query->close();
    $con->close();

    if (!$succes)
        redirect(".?article-id=$article_id&err=" . Error::internal);

    if ($affected_rows <= 0)
        redirect(".?article-id=$article_id&err=" . Error::not_modified);

    redirect(".?article-id=$article_id");