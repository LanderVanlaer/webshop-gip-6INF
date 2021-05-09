<?php
    include '../../../includes/api/api.inc.php';
    include '../../../includes/validateFunctions.inc.php';

    if (!empty($_GET)) {
        $q = var_validate($_GET['q']);

        if (!empty($q)) {
            include '../../../includes/connection.inc.php';

            $query = $con->prepare("SELECT `id`, `name` FROM `country` WHERE `name` LIKE ?");
            $str = "%$q%";
            $query->bind_param('s', $str);
            $query->execute();

            $res = $query->get_result();

            $data = array();
            while ($country = $res->fetch_assoc()) {
                $data[] = $country;
            }
            echo json_encode($data);
        } else {
            echo json_encode(null);
        }
    }