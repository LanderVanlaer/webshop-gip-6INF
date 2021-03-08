<?php
    include '../../../includes/api/api.inc.php';
    include '../../../includes/admin/admin.inc.php';
    include '../../../includes/validateFunctions.inc.php';

    if (!empty($_GET)) {
        $q = var_validate($_GET['q']);

        if (!empty($q)) {
            include '../../../includes/connection.inc.php';

            $query = $con->prepare("SELECT `id`, `name`, `logo` FROM `brand` WHERE `name` LIKE ?");
            $str = "%$q%";
            $query->bind_param('s', $str);
            $query->execute();

            $res = $query->get_result();

            $data = array();
            while ($brand = $res->fetch_assoc()) {
                $data[] = $brand;
            }
            echo json_encode($data);
        } else {
            echo json_encode(null);
        }
    }