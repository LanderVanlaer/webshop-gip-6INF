<?php
    session_start();

    include "../../includes/user/userFunctions.inc.php";
    include "../../includes/basicFunctions.inc.php";

    logout();
    redirect($_SERVER['HTTP_REFERER']);