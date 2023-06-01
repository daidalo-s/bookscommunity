<?php

/**
 * Used in order to guard resources that can only be accessed by the admin of the website
 */

if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION["ADMIN"] == false) {
    header("Location: /tweb-progetto/");
}
