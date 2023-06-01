<?php

include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_get.php";
include_once "../../db/common.php";

/**
 * API route used to get a random book's ISBN
 * from the books in the database
 */

$db = dbConnect();

$query = $db->prepare(
    "SELECT Book.ISBN 
    FROM Book
    ORDER BY RAND()
    LIMIT 1;"
);

try {
    $query->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$random_isbn = $query->fetchAll();

respond(200, $random_isbn);
