<?php
    /**
     * Deze functie maakt een string 'safe to use'
     *
     * @param $var string De string dat moet worden bewerkt
     * @return string De nieuwe 'veilige' waarde
     * @see stripslashes()
     * @see htmlspecialchars()
     * @see trim()
     */
    function var_validate($var) {
        return htmlspecialchars(trim(stripslashes($var)));
    }


    /**
     * Kijkt na of het gegeven wachtwoord gebruikt kan worden. Zo niet, dan krijgt men de errors als {@link string} {@link array}
     *
     * @param $p string Het wachtwoord dat getest moet worden
     * @return string[] Geeft een array van alle errors, als er geen errors zijn een lege array;
     */
    function password_not_possible($p) {
        $arr = array();

        if (!preg_match("/.{8,32}/", $p))
            $arr[] = "Wachtwoord moet uit minstens 8 karakters bestaan en maximaal uit 32";
        if (!preg_match("/[a-z]/", $p))
            $arr[] = "Wachtwoord moet bestaan uit minstens 1 kleine letter";
        if (!preg_match("/[A-Z]/", $p))
            $arr[] = "Wachtwoord moet bestaan uit minstens 1 hoofdletter";
        if (!preg_match("/[0-9]/", $p))
            $arr[] = "Wachtwoord moet bestaan uit minstens 1 cijfer";
        return $arr;
    }

    /**
     * Kijkt na of de meegegeven string een email kan zijn
     *
     * @param $email string De email
     * @return boolean Ofdat de string een email is
     */
    function email_possible($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Kijkt na of 1 van de gegeven arguments empty is
     *
     * @param mixed ...$var De variablen dat nagekeken moeten worden
     * @return bool true als er minstens 1 empty is, anders false
     * @see empty()
     */
    function is_one_empty(...$var) {
        foreach ($var as $i)
            if (empty($i)) return true;
        return false;
    }