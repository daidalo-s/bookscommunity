<?php

include_once "../utils/respond.php";
include_once "../utils/guard_admin.php";
include_once "../utils/guard_get.php";
include_once "../../db/common.php";

/**
 * API route used to get all the stats for the admin 
 * dashboard
 */

$db = dbConnect();

$books_total_query = $db->prepare(
    "SELECT COUNT(Book.ISBN) AS booksNumber
    FROM Book"
);

$books_total_query->execute();
$books_total = $books_total_query->fetchAll();

$review_total_query = $db->prepare(
    "SELECT Count(*) AS reviewNumber
    FROM Review"
);

$review_total_query->execute();
$review_total = $review_total_query->fetchAll();

$upvote_total_query = $db->prepare(
    "SELECT SUM(Review.Upvote + Review.Downvote) AS upvoteTotal
    FROM Review"
);

$upvote_total_query->execute();
$upvote_total = $upvote_total_query->fetchAll();

$usert_total_query = $db->prepare(
    "SELECT COUNT(User.username) AS userTotal
    FROM User"
);

$usert_total_query->execute();
$user_total = $usert_total_query->fetchAll();

$reported_book_query = $db->prepare(
    "SELECT COUNT(*) AS reportedBookTotal
    FROM BookCardReport"
);

$reported_book_query->execute();
$reported_book = $reported_book_query->fetchAll();

$reported_review_total = $db->prepare(
    "SELECT COUNT(*) AS reportedReviewTotal
    FROM BookReviewReport"
);

$reported_review_total->execute();
$reported_review = $reported_review_total->fetchAll();

$result = array();
$result["booksNumber"] = $books_total[0]["booksNumber"];
$result["reviewNumber"] = $review_total[0]["reviewNumber"];
$result["upvoteTotal"] = $upvote_total[0]["upvoteTotal"];
$result["userTotal"] = $user_total[0]["userTotal"];
$result["reportedBookTotal"] = $reported_book[0]["reportedBookTotal"];
$result["reportedReviewTotal"] = $reported_review[0]["reportedReviewTotal"];
$result["username"] = $_SESSION["USERNAME"];
respond(200, $result);
