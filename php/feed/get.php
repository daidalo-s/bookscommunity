<?php
include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_get.php";

/**
 * API route used to populate the home feed
 */

$db = dbConnect();
$query = null;

$query = $db->prepare(
    "SELECT Book.* 
     FROM Book
     ORDER BY Date_added DESC
     LIMIT 10"
);

try {
    $query->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result = $query->fetchAll();

respond(200, $result);
