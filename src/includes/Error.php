<?php

    namespace includes;

    abstract class Error {
        const empty_value = 1;
        const duplicate = 2;
        const file_not_allowed = 3;
        const file_too_large = 4;
        const password_not_match = 6;
        const h_captscha = 7;
        const login_fail = 8;

        public static final function print_message($str) {
            echo "<div class='message'>$str</div>";
        }

        public static final function print_admin_message($error, $executed = false, $succes = false, $last_id = 0) {
            if ($error || $executed) {
                echo "<div class='message'>";
                switch ($error) {
                    case Error::empty_value:
                        echo "Gelieve alle verplichte velden in te vullen";
                        break;
                    case Error::file_not_allowed:
                        echo "U kan geen bestanden uploaden van dit type";
                        break;
                    case Error::file_too_large:
                        echo "Het bestand is te groot";
                        break;
                    case Error::duplicate:
                        echo "Er bestaat er al een met deze naam, id: $last_id";
                        break;
                    case Error::password_not_match:
                        echo "De 2 wachtwoorden zijn niet gelijk aan elkaar";
                        break;
                    case Error::h_captscha:
                        echo "Gelieve de hCaptcha in te vullen";
                        break;
                    case Error::login_fail:
                        echo "Wachtwoord of username fout";
                        break;
                    default:
                        if ($executed) {
                            if ($succes)
                                echo "Toegevoegd met id: $last_id";
                            else
                                echo "Er is iets misgelopen";
                        }
                }
                echo "</div>";
            }
        }
    }