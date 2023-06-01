<?php

include_once "config.php";

/**
 * Function used to connect to the database.
 * @return PDO Connection to the configured database server.
 */
function dbConnect()
{
    try {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $db = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, $options);
    } catch (PDOException $e) {
        respond(500, "Can't connect to database.");
    }
    return $db;
}
