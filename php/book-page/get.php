<?php

include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_get.php";

/**
 * API route used to retrieve all the info's of a given ISBN
 * The result is a combination of all the book-card infos +
 * all the reviews
 */

$isbn = $_GET['isbn'];

$db = dbConnect();

$query1 = null;

$query1 = $db->prepare(
    "SELECT Book.* 
    FROM Book
    WHERE Book.ISBN = :isbn"
);

$query1->bindValue("isbn", $isbn);

try {
    $query1->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

if ($query1->rowCount() == 0) {
    respond(404, "Libro non trovato.");
}

$result1 = $query1->fetchAll();

$query2 = null;

$query2 = $db->prepare(
    "SELECT Review.*, (Review.Upvote-Review.Downvote) AS Rating
    FROM Review
    WHERE Review.ISBN = :isbn
    ORDER BY Rating DESC"
);

$query2->bindValue("isbn", $isbn);

try {
    $query2->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result2 = $query2->fetchAll();

$result = array_merge($result1, $result2);

respond(200, $result);
