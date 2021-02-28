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