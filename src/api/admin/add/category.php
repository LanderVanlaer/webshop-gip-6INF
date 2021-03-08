<?php
    include '../../../includes/api/api.inc.php';
    include '../../../includes/admin/admin.inc.php';
    include '../../../includes/validateFunctions.inc.php';

    function generateCategoryObject($id, $nameD, $nameF, $nameE) {
        return array(
            'id' => $id,
            'nameD' => $nameD,
            'nameF' => $nameF,
            'nameE' => $nameE,
            'specs' => array()
        );
    }

    if (!empty($_GET)) {
        $q = var_validate($_GET['q']);

        if (!empty($q)) {
            include '../../../includes/connection.inc.php';

            $query = $con->prepare(file_get_contents("../../../sql/api/category.sql"));
            $str = "%$q%";
            $query->bind_param('sss', $str, $str, $str);
            $query->execute();
            $res = $query->get_result();

            $data = array();

            if ($res->num_rows > 0) {
                $cat = null;
                while ($spec = $res->fetch_assoc()) {
                    $specData = array(
                        'id' => $spec['sid'],
                        'nameD' => $spec['snameD'],
                        'nameF' => $spec['snameF'],
                        'nameE' => $spec['snameE']
                    );

                    if ($cat != null && $spec['cid'] == $cat['id']) {
                        $cat['specs'][] = $specData;
                    } else {
                        if ($cat != null)
                            $data[] = $cat;
                        $cat = generateCategoryObject($spec['cid'], $spec['cnameD'], $spec['cnameF'], $spec['cnameE']);
                        $cat['specs'][] = $specData;
                    }
                }
                $data[] = $cat;
            }

            echo json_encode($data);
        } else {
            echo json_encode(array());
        }
    } else {
        echo json_encode(array());
    }