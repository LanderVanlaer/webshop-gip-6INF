<?php

    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";
    include_once "../../../includes/fileFuncties.inc.php";

    $article_id = var_validate($_POST['article-id']);
    $images = format_file_array($_FILES['files']);

    if (is_one_empty($article_id, $images)) {
        redirect(".?article-id=$article_id&err=" . Error::empty_value);
    }

    if (!is_numeric($article_id))
        redirect(".?article-id=$article_id&err=" . Error::bad_request);

    //nakijken of de gegeven images weldeglijk images zijn
    //nakijken of de gegeven images niet te groot zijn
    $bytes = 2500000; //2.5 MB
    $are_images = $images_file_size_ok = true;
    foreach ($images as $image) {
        if (!is_image($image)) {
            $are_images = false;
            break;
        }
        if (!file_size_less($image, $bytes)) {
            $images_file_size_ok = false;
            break;
        }
    }

    if (!$are_images)
        redirect(".?article-id=$article_id&err=" . Error::file_not_allowed);
    if (!$images_file_size_ok)
        redirect(".?article-id=$article_id&err=" . Error::file_too_large);

    include_once "../../../includes/connection.inc.php";

    $path = null;
    $query = $con->prepare("INSERT INTO articleimage(path, article_id, isThumbnail) VALUES (?, ?, 0)");
    $query->bind_param('si', $path, $article_id);

    foreach ($images as $image) {
        $path = file_save($image, "../../../images/articles");
        $query->execute();
    }
    $query->close();

    $con->close();
    redirect(".?article-id=$article_id");