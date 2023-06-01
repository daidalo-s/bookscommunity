<?php

include_once "../utils/guard_admin.php";
include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_post.php";

/**
 * API route used to delete a reported review
 */

$db = dbConnect();

// We are deleting, first the review is deleted than the record
// of it in the report table
if (!isset($_POST["isbn"]) || !isset($_POST["author"])) {
    respond_not_valid("Parametri errati");
}

$isbn = $_POST["isbn"];
$author = $_POST["author"];

// First we delete the review
$delete_reported_review = $db->prepare(
    "DELETE FROM Review
    WHERE Review.ISBN = :isbn
    AND Review.Author = :author"
);

$delete_reported_review->bindValue("isbn", $isbn);
$delete_reported_review->bindValue("author", $author);

try {
    $delete_reported_review->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

// Now we delete the report
$mark_review_safe = $db->prepare(
    "DELETE FROM BookReviewReport
    WHERE BookReviewReport.ISBN = :isbn 
    AND BookReviewReport.Author = :author"
);

$mark_review_safe->bindValue("isbn", $isbn);
$mark_review_safe->bindValue("author", $author);

try {
    $mark_review_safe->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

respond(200, "Success");
