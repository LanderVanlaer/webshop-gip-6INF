<?php
    /**
     * Checks whether the current {@link $_SESSION} user is an admin
     *
     * @return bool Whether the client is an admin
     */
    function isAdmin() {
        return !empty($_SESSION["admin"]) && !empty($_SESSION["admin"]["id"]);
    }