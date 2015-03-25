<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function pms_service($message)
{
    $JSONObject = json_decode($message, true);
    if (!empty($JSONObject['s'])) {
        return $JSONObject['s'];
    }
}

function pms_method($message)
{
    $JSONObject = json_decode($message, true);
    if (!empty($JSONObject['m'])) {
        return $JSONObject['m'];
    }
}

function pms_params($message)
{
    $JSONObject = json_decode($message, true);
    if (!empty($JSONObject['p'])) {
        return $JSONObject['p'];
    }
}
