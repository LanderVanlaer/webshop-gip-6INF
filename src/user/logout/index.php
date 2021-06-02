<?php
    session_start();

    include_once "../../includes/user/userFunctions.inc.php";
    include_once "../../includes/basicFunctions.inc.php";

    logout();
    redirect($_SERVER['HTTP_REFERER']);