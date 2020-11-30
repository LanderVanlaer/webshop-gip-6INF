<?php
    //https://www.php.net/manual/en/mysqlinfo.api.choosing.php
    $mysqli = new mysqli("localhost:3306", "root", "usbw", "gigacam");
    if ($mysqli->connect_errno) {
        throw new mysqli_sql_exception("Error with connection of database");
    }
