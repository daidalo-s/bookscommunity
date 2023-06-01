<?php

include_once "../../db/common.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_get.php";
include_once "../utils/respond.php";

/**
 * API route used to retrieve all the search result of 
 * a user query
 */

$user_param = $_GET["keyword"];
$search_query = "%" . $user_param . "%";

$db = dbConnect();

// Checking that the user query is either a auhtor's name or
// book's title
$query = $db->prepare(
    "SELECT Book.*
    FROM Book
    WHERE LOWER(Book.Title) LIKE :query OR LOWER(Book.Author) LIKE :query
    ORDER BY Book.Date_added DESC;"
);

$query->bindValue("query", $search_query);

try {
    $query->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result = $query->fetchAll();

respond(200, $result);
