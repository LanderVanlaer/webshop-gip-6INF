<?php


    namespace Mail;


    function mail_send_activate($to_email, $firstname, $lastname, $registration_code) {
        ini_set("SMTP", "smtp.eu.mailgun.org");
        ini_set("smtp_port", 587);

        $header = "From:GigaCam <no-reply@mail.gigacam.be>\r\nContent-type:text/html;charset=UTF-8\r\n";
        $link = "http://{$_SERVER['SERVER_NAME']}/user/activate?code=$registration_code";
        return mail(
            $to_email,
            "Registratie gigacam.be",
//                "Beste <i>$firstname $lastname</i><br>gelieve deze email te valideren via onderstaande link:\n\t<a href='$link'>$link</a>",
            str_replace("{{name}}", "$firstname $lastname", str_replace("{{activation_link}}", $link, file_get_contents(__DIR__ . "/../resources/mail/activate/" . language() . ".html"))),
            $header
        );
    }


    function mail_send_password_recovery($to_email, $firstname, $lastname, $registration_code) {
        ini_set("SMTP", "smtp.eu.mailgun.org");
        ini_set("smtp_port", 587);

        $header = "From:GigaCam <no-reply@mail.gigacam.be>\r\nContent-type:text/html;charset=UTF-8\r\n";
        $link = "http://{$_SERVER['SERVER_NAME']}/user/login/forgot-password/reset?code=$registration_code";
        return mail(
            $to_email,
            "Wachtwoord herstellen",
            str_replace("{{name}}", "$firstname $lastname", str_replace("{{activation_link}}", $link, file_get_contents(__DIR__ . "/../resources/mail/password-reset/" . language() . ".html"))),
            $header
        );
    }