<?php
    //https://www.php.net/manual/en/mysqlinfo.api.choosing.php
    $mysqli = new mysqli("localhost:3306", "root", "usbw", "gigacam");

    if ($mysqli->connect_errno) die("Error with connection to database {$mysqli->connect_error}");