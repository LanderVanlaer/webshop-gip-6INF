<?php
    /**
     * Redirect de gebruiker naar een andere url
     *
     * @param string $url De url naar waar de persoon naar toe moet worden gestuurt
     * @param string $params De GET paramaters
     */
    function redirect(string $url, string $params = '') {
        if (empty($params))
            header("Location: $url" . (!empty($params) ? '?' . urlencode($params) : ''));
        die();
    }

    /**
     * Geeft een array terug waar alle keys van de vorige array matchen met de regular expression
     *
     * @param $regex string De regular expression
     * @param $array array Een associatieve array
     * @return array Een nieuwe associatieve array waar de keys voldoen aan de gegeven regex
     */
    function preg_grep_keys(string $regex, array $array): array {
        $return = array();

        foreach ($array as $key => $value) {
            if (preg_match($regex, $key))
                $return[$key] = var_validate($value);
        }

        return $return;
    }

    /**
     * Kijkt na of de 2 string hetzelfde zijn.
     * Alleen gebruiken als men {@link $_GET} waardes gebruikt
     *
     * @param string $str1
     * @param string $str2
     * @return bool < 0 als str1 kleiner is dan str2; > 0 als str1 groter is dan str2, en 0 als ze gelijk zijn.
     * @see strcmp()
     */
    function str_compare_GET(string $str1, string $str2): bool {
        return str_replace("+", " ", $str1) == str_replace("+", " ", $str2);
    }

    /**
     * Geeft de letter van de gekozen taal terug
     *
     * @param bool $capital Of de letter een hoofdletter moet zijn
     * @return string Engels = e/E;<br>Frans = f/F;<br>Nederlands = d/D
     */
    function language(bool $capital = false): string {
        if (empty($_COOKIE['lang']) || !in_array($_COOKIE['lang'], ['e', 'f', 'd']))
            setcookie('lang', 'e', time() + 3600 * 24 * 365);

        return $capital ? strtoupper($_COOKIE['lang']) : $_COOKIE['lang'];
    }