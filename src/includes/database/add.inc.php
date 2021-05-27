<?php
    function db_add_address($con, $country_id, $township, $street, $streetNumber) {
        //township
        $query = $con->prepare(file_get_contents("../../sql/address/township/select.sql"));
        $query->bind_param('si', $township, $country_id);
        $query->execute();
        $res = $query->get_result()->fetch_assoc();
        $query->close();

        if ($res) {
            $township_id = $res['id'];
        } else {
            $query = $con->prepare(file_get_contents("../../sql/address/township/insert.sql"));
            $query->bind_param('si', $township, $country_id);
            $query->execute();
            $township_id = $query->insert_id;
            $query->close();
        }

        //street
        $query = $con->prepare(file_get_contents("../../sql/address/street/select.sql"));
        $name = "%$street%";
        $query->bind_param('si', $street, $township_id);
        $query->execute();
        $res = $query->get_result()->fetch_assoc();
        $query->close();

        if ($res) {
            $street_id = $res['id'];
        } else {
            $query = $con->prepare(file_get_contents("../../sql/address/street/insert.sql"));
            $query->bind_param('si', $street, $township_id);
            $query->execute();
            $street_id = $query->insert_id;
            $query->close();
        }

        //address
        $query = $con->prepare(file_get_contents("../../sql/address/address/select.sql"));
        $query->bind_param('ii', $streetNumber, $street_id);
        $query->execute();
        $res = $query->get_result()->fetch_assoc();
        $query->close();

        if ($res) {
            $address_id = $res['id'];
        } else {
            $query = $con->prepare(file_get_contents("../../sql/address/address/insert.sql"));
            $query->bind_param('ii', $streetNumber, $street_id);
            $query->execute();
            $address_id = $query->insert_id;
            $query->close();
        }

        return $address_id;
    }