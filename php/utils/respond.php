<?php

/**
 * Utility function used to respond using JSON format with HTTP.
 *
 * @param code The response code to send.
 * @param message The message to send.
 */
function respond($code, $message)
{
    http_response_code($code);
    header("Content-type: application/json");
    die(json_encode(["statusCode" => $code, "message" => $message]));
}

/**
 * Utility function used when the user input is not valid.
 *
 * @param message The error message to send.
 */
function respond_not_valid($message)
{
    respond(400, $message);
}
