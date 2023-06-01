<?php

include_once "../../db/common.php";
include_once "../utils/guard_get.php";
include_once "../utils/guard_auth.php";
include_once "../utils/respond.php";

/**
 * API route to retrieve the user stats
 *
 */

$username = $_SESSION["USERNAME"];

$db = dbConnect();

$books_num_query = $db->prepare(
    "SELECT COUNT(DISTINCT(Book.ISBN)) AS numOfBooks
    FROM Book JOIN Review ON Book.ISBN = Review.ISBN
    WHERE Review.Author = :username"
);

$books_num_query->bindValue("username", $username);

try {
    $books_num_query->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$books_num = $books_num_query->fetchAll();
$books_num = $books_num[0]["numOfBooks"];


$rating_query = $db->prepare(
    "SELECT SUM(Review.Upvote - Review.Downvote) AS rating
    FROM Review
    WHERE Review.Author = :username"
);

$rating_query->bindValue("username", $username);

try {
    $rating_query->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$rating = $rating_query->fetchAll();
$rating = $rating[0]["rating"];

$result = [
    "username" => $username,
    "numbooks" => $books_num,
    "upvotes" => $rating
];

respond(200, $result);
