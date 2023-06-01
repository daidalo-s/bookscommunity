<?php

include_once "../utils/guard_admin.php";
include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_post.php";

/**
 * API route used to remove a review report after
 * admin approval
 */

$db = dbConnect();

if (!isset($_POST["isbn"]) || !isset($_POST["author"])) {
    respond_not_valid("Parametri errati");
}

$isbn = $_POST["isbn"];
$author = $_POST["author"];

// We onyl care about deleting the right row
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
    respond(500, $e);
}

respond(200, "Recensione approvata");
