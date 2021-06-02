<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    $article_id = var_validate($_POST['article-id']);
    $categories = $_POST['categories'];

    if (is_one_empty($article_id, $categories))
        redirect(".?article-id=$article_id&err=" . Error::empty_value);

    if (!is_numeric($article_id))
        redirect(".?article-id=$article_id&err=" . Error::bad_request);

    $categories_arr_int = array_keys($categories);
    foreach ($categories_arr_int as $i) {
        if (!is_numeric($i))
            redirect(".?article-id=$article_id&err=" . Error::bad_request);
    }

    include_once "../../../includes/connection.inc.php";

    $category_id = -1;
    $affected_rows = 0;

    $query = $con->prepare("DELETE FROM articlecategory WHERE article_id = ? AND category_id = ?");
    $query->bind_param('ii', $article_id, $category_id);

    $querySpecArt = $con->prepare("DELETE articlespecification FROM articlespecification INNER JOIN specification s on articlespecification.specification_id = s.id WHERE article_id = ? AND s.category_id = ?");
    $querySpecArt->bind_param('ii', $article_id, $category_id);

    foreach ($categories_arr_int as $category_id) {
        $succes = $query->execute() && $querySpecArt->execute();

        if (!$succes) {
            $query->close();
            $con->close();
            redirect(".?article-id=$article_id&err=" . Error::internal);
        }


        $affected_rows += $query->affected_rows;
    }

    $query->close();

    redirect(".?article-id=$article_id");