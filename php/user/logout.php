<?php

include_once "../utils/respond.php";
include_once "../utils/guard_post.php";
include_once "../utils/cookie_terminator.php";

/**
 * Quando arrivo qua cosa succede? Succede
 * 
 */

session_start();

if (isset($_SESSION)) {
    session_unset();
    session_destroy();
    cookie_terminator();
}

respond(200, "index.php");
