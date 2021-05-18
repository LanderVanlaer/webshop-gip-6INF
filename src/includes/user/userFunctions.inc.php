<?php
    /**
     * Checks if the current {@link $_SESSION} user exists
     *
     * @return bool Whether the client is logged in
     */
    function isLoggedIn() {
        return !empty($_SESSION["user"]) && !empty($_SESSION["user"]["id"]);
    }

    /**
     * Logs the customer out
     */
    function logout() {
        unset($_SESSION['user']);
    }