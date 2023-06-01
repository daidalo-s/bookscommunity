<?php

/**
 * Function used to delete the PHPSESSID cookie when the session
 * has expired.
 */
function cookie_terminator()
{
    setcookie("PHPSESSID", "", time() - 3600, "/");
}
