<?php

include_once "../utils/guard_admin.php";
include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_get.php";

/**
 * API route used to get a single reported review
 * content
 */

$db = dbConnect();

$isbn = $_GET["isbn"];
$author = $_GET["author"];

$get_review_content = $db->prepare(
    "SELECT Review.Content
    FROM Review
    WHERE Review.ISBN = :isbn AND 
    Review.Author = :author"
);

$get_review_content->bindValue("isbn", $isbn);
$get_review_content->bindValue("author", $author);

try {
    $get_review_content->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result = $get_review_content->fetchAll();

respond(200, $result);
