<?php

include_once "../utils/guard_admin.php";
include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_get.php";

/**
 * API route used to retrieve all the reported reviews
 */

$db = dbConnect();

$get_reviews = $db->prepare(
    "SELECT Review.Title, Review.Author, Review.ISBN
    FROM Review JOIN BookReviewReport ON 
    Review.ISBN = BookReviewReport.ISBN AND 
    Review.Author = BookReviewReport.Author;"
);

$get_reviews->execute();
$result = $get_reviews->fetchAll();

respond(200, $result);
