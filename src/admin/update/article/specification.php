<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";

    $article_id = var_validate($_POST['article-id']);
    $specifications = $_POST['specifications'];

    if (is_one_empty($article_id, $specifications))
        redirect(".?article-id=$article_id&err=" . Error::empty_value);

    foreach ($specifications as $specification_id => $specification_value) {
        if (empty($specification_id))
            redirect(".?article-id=$article_id&err=" . Error::bad_request);

        if (empty($specification_value))
            redirect(".?article-id=$article_id&err=" . Error::empty_value);
    }

    if (!is_numeric($article_id))
        redirect(".?article-id=$article_id&err=" . Error::bad_request);

    $specifications_arr_int = array_keys($specifications);
    foreach ($specifications_arr_int as $i) {
        if (!is_numeric($i))
            redirect(".?article-id=$article_id&err=" . Error::bad_request);
    }

    include "../../../includes/connection.inc.php";

    $specification_id = -1;
    $specification_value = -1;
    $affected_rows = 0;

    $query = $con->prepare("UPDATE articlespecification SET value = ? WHERE id = ?;");
    $query->bind_param('si', $specification_value, $specification_id);

    foreach ($specifications as $specification_id => $specification_value) {
        $succes = $query->execute();

        echo "$specification_id => $specification_value";

        if (!$succes) {
            $query->close();
            $con->close();
            redirect(".?article-id=$article_id&err=" . Error::internal);
        }


        $affected_rows += $query->affected_rows;
    }

    $query->close();

    redirect(".?article-id=$article_id");