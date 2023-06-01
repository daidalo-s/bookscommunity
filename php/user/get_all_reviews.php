<?php

include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_get.php";

/**
 * API route used to get all the books reviewd by 
 * one user
 */

// Retrieving the username
$username = $_SESSION["USERNAME"];

$db = dbConnect();

$book_data = $db->prepare(
    "SELECT Book.*
    FROM Book JOIN Review ON Book.ISBN = Review.ISBN
    WHERE Review.Author = :username"
);

$book_data->bindValue("username", $username);

try {
    $book_data->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result = $book_data->fetchAll();

respond(200, $result);
