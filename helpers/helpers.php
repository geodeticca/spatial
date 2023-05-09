<?php

if (!function_exists('is_filename')) {

    function is_filename($filename)
    {
        return strpos($filename, '.') !== false;
    }
}
