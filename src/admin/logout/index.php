<?php
    include_once "../../includes/basicFunctions.inc.php";
    session_start();
    session_unset();
    redirect("/admin/login");