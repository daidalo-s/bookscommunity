<?php

/**
 * Used to redirect the admin to the admin panel
 */

if (isset($_COOKIE["PHPSESSID"])) {
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION["USERNAME"]) && $_SESSION["ADMIN"] == true) {
        header("Location: /tweb-progetto/html/admin-home.php");
    }
}
