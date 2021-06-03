<?php
    include_once "../../../includes/Error.php";

    use includes\Error as Error;

    include_once "../../../includes/admin/admin.inc.php";
    include_once "../../../includes/validateFunctions.inc.php";

    print_r($_POST);

    $brand_id = var_validate($_POST['brand-id']);
    $name = var_validate($_POST['name']);

    if (is_one_empty($brand_id, $name))
        redirect(".?brand-id=$brand_id&err=" . Error::empty_value);

    if (!is_numeric($brand_id))
        redirect(".?brand-id=$brand_id&err=" . Error::bad_request);


    include_once "../../../includes/connection.inc.php";

    $query = $con->prepare("UPDATE brand set name = ? WHERE id = ?");
    $query->bind_param('si', $name, $brand_id);

    $succes = $query->execute();
    $affected_rows = $query->affected_rows;

    $query->close();
    $con->close();

    if (!$succes)
        redirect(".?brand-id=$brand_id&err=" . Error::internal);

    if ($affected_rows <= 0)
        redirect(".?brand-id=$brand_id&err=" . Error::not_modified);

    redirect(".?brand-id=$brand_id");