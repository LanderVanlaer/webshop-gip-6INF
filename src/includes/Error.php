<?php

    namespace includes;

    include_once "basicFunctions.inc.php";
    const __LANGUAGE_JSON_FILE_NAME_ERROR_MESSAGES__ = __DIR__ . "\\..\\resources\\languages\\error.messages.json";

    abstract class Error
    {
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


        public static final function print_admin_message($error, $executed = false, $succes = false, $last_id = 0) {
            if (empty($error) && empty($executed)) return;

            if (!empty($error)) {
                self::print_message(str_replace("{id}", $last_id, self::get_error_messages()[$error]));
                return;
            }

            if (!$executed) return;

            if (!$succes) {
                self::print_message(self::get_error_messages()['executed']);
                return;
            };

            if ($last_id) {
                self::print_message(str_replace("{id}", $last_id, self::get_error_messages()['succes_last_id']));
                return;
            }

            self::print_message(self::get_error_messages()['succes']);
        }

        public static final function print_message($str) {
            echo "<div class='message'>$str</div>";
        }

        public static final function get_error_messages() {
            return json_decode(file_get_contents(__LANGUAGE_JSON_FILE_NAME_ERROR_MESSAGES__), true)[language()];
        }
    }