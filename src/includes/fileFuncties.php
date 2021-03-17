<?php
    /**
     * De bestand extensies die gebruikt mogen worden voor het uploaden van foto's (images)
     */
    define("UPLOAD_IMAGE_EXTENSIONS", array('jpg', 'jpeg', 'png', 'svg'));

    /**
     * Kijkt na of de gegeven array een file is.
     *
     * @param $file array Een array
     * @return bool ofdat de gegeven array een bestand vormt
     */
    function is_file($file) {
        return isset($file)
            && isset($file["name"])
            && isset($file["type"])
            && isset($file["tmp_name"])
            && isset($file["error"])
            && isset($file["size"]);
    }

    /**
     * Kijkt na ofdat het gegeven bestand een image is.
     *
     * @param $file array Een image
     * @return bool ofdat het bestand een image is
     * @see UPLOAD_IMAGE_EXTENSIONS
     */
    function is_image($file) {
        $f = explode(".", $file["name"]);
        return in_array(end($f), UPLOAD_IMAGE_EXTENSIONS);
    }

    /**
     * Maakt een array dat bestaat uit files
     *
     * @param $array array Een gedeelte van {@link $_FILES}
     * @return array De files in een bruikbaar formaat
     */
    function format_file_array($array) {
        $files = array();
        if (!empty($array['name'])) {
            for ($i = 0; $i < count(array()); $i++) {
                $files[] = array(
                    "name" => $array["name"][$i],
                    "type" => $array["type"][$i],
                    "tmp_name" => $array["tmp_name"][$i],
                    "error" => $array["error"][$i],
                    "size" => $array["size"][$i],
                );
            }
        }
        return $files;
    }

    /**
     * Zegt ofdat de file kleiner dan of gelijk aan de gegeven bytes is.
     *
     * @param $file array Het bestand
     * @param $byte int Het aantal bytes waar het bestand kleiner dan of gelijk aan moet zijn
     * @return bool ofdat de gegeven array een file is en ofdat de file kleiner dan of gelijk aan de gegeven bytes is
     */
    function file_size_less($file, $byte) {
        return is_file($file) && $file['size'] <= $byte;
    }