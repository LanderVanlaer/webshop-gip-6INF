<?php
    include_once __DIR__ . "/adminFunctions.inc.php";
    include_once __DIR__ . "/../basicFunctions.inc.php";

    session_start();

    if (!isAdmin()) redirect("/admin/login/");