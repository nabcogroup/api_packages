<?php


if(!function_exists("extract_unset")) {

    function extract_unset($data, $key) {
        $extract = $data[$key];
        unset($data[$key]);
        return $extract;
    }
}