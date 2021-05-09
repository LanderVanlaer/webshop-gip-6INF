<?php
    const H_CAPTCHA_API_KEY = '3e7a1904-27c7-4bfc-b392-99c68f690f23';

    function h_captcha_verify($h_captcha_response) {

        $data = array(
            'secret' => "0x1D3C92B9e7D52594976A623BFE8f870884e4A154",
            'response' => $h_captcha_response
        );

        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($verify);

        return json_decode($response);
    }