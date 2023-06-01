<?php

/**
 * Used in order to guard resources that can only be accessed by authenticated users
 */

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["USERNAME"])) {
    header("Location: /tweb-progetto/");
}
