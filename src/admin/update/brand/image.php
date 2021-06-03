<?php

    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";
    include_once "../../../includes/fileFuncties.inc.php";

    $brand_id = var_validate($_POST['brand-id']);
    $image = $_FILES['image'];

    if (is_one_empty($brand_id, $image)) {
        redirect(".?brand-id=$brand_id&err=" . Error::empty_value);
    }

    if (!is_numeric($brand_id))
        redirect(".?brand-id=$brand_id&err=" . Error::bad_request);

    //nakijken of de gegeven images weldeglijk images zijn
    //nakijken of de gegeven images niet te groot zijn
    $bytes = 2500000; //2.5 MB
    if (!is_image($image))
        redirect(".?brand-id=$brand_id&err=" . Error::file_not_allowed);
    if (!file_size_less($image, $bytes))
        redirect(".?brand-id=$brand_id&err=" . Error::file_too_large);


    include_once "../../../includes/connection.inc.php";
    $query = $con->prepare("UPDATE brand SET logo = ? WHERE id = ?");
    $path = file_save($image, "../../../images/brands");
    $query->bind_param('si', $path, $brand_id);
    $query->execute();


    $query->close();

    $con->close();
    redirect(".?brand-id=$brand_id");