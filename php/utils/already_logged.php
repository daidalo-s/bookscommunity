<?php

include_once "cookie_terminator.php";

/**
 * Used to redirect a user that has a valid cookie directly to the website home
 */
if (isset($_COOKIE["PHPSESSID"])) {
    session_start();

    if (isset($_SESSION["USERNAME"])) {
        header("Location: /tweb-progetto/html/home.php");
    } else {
        session_unset();
        session_destroy();
        cookie_terminator();
    }
}
