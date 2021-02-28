<?php
    //https://www.php.net/manual/en/mysqlinfo.api.choosing.php
    $con = new mysqli("localhost:3306", "root", "", "gigacam");

    if ($con->connect_errno) die("Error with connection to database {$con->connect_error}");