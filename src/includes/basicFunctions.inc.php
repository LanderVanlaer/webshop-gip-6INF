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
     * Geeft een array terug waar alle keys van de vorige array matchen met de regular expression
     *
     * @param $regex string De regular expression
     * @param $array array Een associatieve array
     * @return array Een nieuwe associatieve array waar de keys voldoen aan de gegeven regex
     */
    function preg_grep_keys($regex, $array) {
        $return = array();

        foreach ($array as $key => $value) {
            if (preg_match($regex, $key))
                $return[$key] = $value;
        }

        return $return;
    }
