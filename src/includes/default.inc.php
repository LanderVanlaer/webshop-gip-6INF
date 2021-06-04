<?php
    include_once "basicFunctions.inc.php";
    language();

    const __LANGUAGE_JSON_FILE_NAME_FOOTER_HEADER__ = __DIR__ . "\\..\\resources\\languages\\default.json";

    $json = json_decode(file_get_contents(__LANGUAGE_JSON_FILE_NAME_FOOTER_HEADER__), true)[language()];


    define("__LANGUAGE_JSON_FILE_NAME__", str_replace('/', '\\', __DIR__ . "/../resources/languages" . dirname($_SERVER['SCRIPT_NAME']) . ".json"));

    if (file_exists(__LANGUAGE_JSON_FILE_NAME__)) {
        $json = array_merge($json, json_decode(file_get_contents(__LANGUAGE_JSON_FILE_NAME__), true)[language()]);
    }

    define('_', $json);