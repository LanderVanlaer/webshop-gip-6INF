<?php

    namespace includes;

    abstract class Error {
        const empty_value = 1;
        const duplicate = 2;
        const file_not_allowed = 3;
        const file_too_large = 4;
        const password_not_match = 6;
        const h_captcha = 7;
        const login_fail = 8;
        const email_not_valid = 9;
        const duplicate_customer = 10;
        const password_not_possible = 11;
        const internal = 12;
        const customer_not_found_by_email = 13;
        const customer_not_active = 14;
        const bad_request = 15; //400
        const not_modified = 16; //304

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
                    case Error::h_captcha:
                        echo "Gelieve de hCaptcha in te vullen";
                        break;
                    case Error::login_fail:
                        echo "Wachtwoord of username fout";
                        break;
                    case Error::email_not_valid:
                        echo "Het gegeven emailadres is niet geldig";
                        break;
                    case Error::duplicate_customer:
                        echo "Er bestaat al een account met deze email";
                        break;
                    case Error::password_not_possible:
                        echo "Wachtwoord moet uit minstens 1 kleine letter, 1 hoofdletter, 1 cijfer en minimum 8 karakters bestaan en maximaal uit 32";
                        break;
                    case Error::customer_not_found_by_email:
                        echo "Er bestaat geen gebruiker met deze email";
                        break;
                    case Error::customer_not_active:
                        echo "Gelieve uw mailadres te verifiëren";
                        break;
                    case Error::bad_request:
                        echo "Er is iets fout gelopen bij de aanvraag, gelieve later terug opnieuw te proberen";
                        break;
                    case Error::internal:
                        echo "Internal Error, please contact the customer service or try again later";
                        break;
                    case Error::not_modified:
                        echo "Niet gewijzigd";
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