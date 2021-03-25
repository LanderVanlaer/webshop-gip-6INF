<?php
    include '../includes/api/api.inc.php';
    include '../includes/admin/admin.inc.php';
    include '../includes/validateFunctions.inc.php';

    $data = array("categories" => array(), "articles" => array());

    if (!empty($_GET)) {
        $q = var_validate($_GET['q']);


        if (!empty($q)) {
            include '../includes/connection.inc.php';

            $str = "%$q%";

            // CATEGORIES
            $query = $con->prepare(file_get_contents("../sql/api/search/category.sql"));
            $query->bind_param('sss', $str, $str, $str);
            $query->execute();
            $res = $query->get_result();

            $categories = array();

            if ($res->num_rows > 0)
                $categories = $res->fetch_all(MYSQLI_ASSOC);

            $data['categories'] = $categories;


            // ARTICLES
            $query = $con->prepare(file_get_contents("../sql/api/search/article.sql"));
            $query->bind_param('s', $str);
            $query->execute();
            $res = $query->get_result();

            $articles = array();

            if ($res->num_rows > 0)
                $articles = $res->fetch_all(MYSQLI_ASSOC);

            $data['articles'] = $articles;


            echo json_encode($data);
        } else {
            echo json_encode($data);
        }
    } else {
        echo json_encode($data);
    }