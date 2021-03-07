<?php
    /**
     * Redirect de gebruiker naar een andere url
     *
     * @param $url string De url naar waar de persoon naar toe moet worden gestuurt
     */
    function redirect($url) {
        header("Location: $url");
        die();
    }

    /**
     * Checks whether the current {@link $_SESSION} user is an admin
     *
     * @return bool Whether the client is an admin
     */
    function isAdmin() {
        return !empty($_SESSION["admin"]) && !empty($_SESSION["admin"]["id"]);
    }