<?php

include_once "../utils/guard_admin.php";
include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_get.php";

/**
 * API route used to get all the reported books
 */

$db = dbConnect();

$get_books = $db->prepare(
    "SELECT Book.Title, Book.ISBN
    FROM Book JOIN BookCardReport ON 
    Book.ISBN = BookCardReport.ISBN;"
);

$get_books->execute();
$result = $get_books->fetchAll();

respond(200, $result);
