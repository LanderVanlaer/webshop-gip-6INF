<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include "../../../includes/admin/admin.inc.php";
    include "../../../includes/validateFunctions.inc.php";

    $article_id = var_validate($_POST['article-id']);
    $images = $_POST['images'];

    if (is_one_empty($article_id, $images))
        redirect(".?article-id=$article_id&err=" . Error::empty_value);

    if (!is_numeric($article_id))
        redirect(".?article-id=$article_id&err=" . Error::bad_request);

    $images_arr_int = array_keys($images);
    foreach ($images_arr_int as $i) {
        if (!is_numeric($i))
            redirect(".?article-id=$article_id&err=" . Error::bad_request);
    }

    include "../../../includes/connection.inc.php";

    $image_id = -1;
    $affected_rows = 0;

    $query = $con->prepare("DELETE FROM articleimage WHERE id = ?");
    $query->bind_param('i', $image_id);

    foreach ($images_arr_int as $image_id) {
        $succes = $query->execute();

        if (!$succes) {
            $query->close();
            $con->close();
            redirect(".?article-id=$article_id&err=" . Error::internal);
        }

        $affected_rows += $query->affected_rows;
    }

    $query->close();

    if ($affected_rows <= 0) {
        $con->close();
        redirect(".?article-id=$article_id&err=" . Error::not_modified);
    }

    $query = $con->prepare("SELECT count(id) AS amount FROM articleimage WHERE article_id = ?;");
    $query->bind_param('i', $article_id);
    $query->execute();

    $res = $query->get_result();
    $amount = $res->fetch_assoc()['amount'];
    $query->close();

    if ($amount <= 0) {
        $query = $con->prepare("INSERT INTO articleimage(path, article_id, isThumbnail) VALUES('../404.png', ?, 1)");
        $query->bind_param('i', $article_id);
        $query->execute();

        $query->close();
        $con->close();
        redirect(".?article-id=$article_id");
    }

    $query = $con->prepare("SELECT count(isThumbnail) as amount FROM articleimage WHERE article_id = ? AND isThumbnail LIMIT 1;");
    $query->bind_param('i', $article_id);
    $query->execute();

    $res = $query->get_result();
    $amount_thumbnails = $res->fetch_assoc()['amount'];
    $query->close();

    if ($amount_thumbnails <= 0) {
        $query = $con->prepare("UPDATE articleimage SET isThumbnail = 1 WHERE id = (SELECT id FROM articleimage WHERE article_id = ? LIMIT 1)");
        $query->bind_param('i', $article_id);
        $query->execute();
        $query->close();
    }

    $con->close();
    redirect(".?article-id=$article_id");