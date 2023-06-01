<?php
include_once "respond.php";

/**
 * Used to check if the request method is the right one for the API route.
 * In this case, we check for the POST request.
 */

if (!isset($_SERVER["REQUEST_METHOD"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
    respond(405, "Metodo sbagliato, solo POST accettato");
}
