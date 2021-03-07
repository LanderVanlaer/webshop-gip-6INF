<?php
    include __DIR__ . "/adminFunctions.inc.php";
    include __DIR__ . "/../basicFunctions.inc.php";

    session_start();

    if (!isAdmin()) redirect("/admin/login/");