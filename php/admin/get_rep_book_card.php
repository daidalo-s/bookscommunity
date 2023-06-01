<?php

include_once "../utils/respond.php";
include_once "../../db/common.php";
include_once "../utils/guard_get.php";
include_once "../utils/guard_admin.php";

/**
 * API route used to get all the informations of a 
 * reported book
 */

if (!isset($_GET["isbn"])) {
    respond(400, "Parametri errati");
}

$db = dbConnect();

$book_review_content = $db->prepare(
    "SELECT Book.Title, Book.ISBN, Book.Author, Book.Genre, Book.Description, Book.Year
    FROM Book
    WHERE Book.ISBN = :isbn"
);

$book_review_content->bindValue("isbn", $_GET["isbn"]);

try {
    $book_review_content->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result = $book_review_content->fetchAll();

respond(200, $result);
