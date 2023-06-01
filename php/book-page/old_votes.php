<?php

include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_get.php";
include_once "../../db/common.php";

/**
 * API route used to retrieve all the likes of a given 
 * book's isbn
 */

$isbn = $_GET["isbn"];
$user = $_SESSION["USERNAME"];

$db = dbConnect();

$all_likes = $db->prepare(
    "SELECT Likes.*
    FROM Likes
    WHERE Likes.ISBN = :isbn
    AND Likes.Username = :username"
);

$all_likes->bindValue("isbn", $isbn);
$all_likes->bindValue("username", $user);

try {
    $all_likes->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result = $all_likes->fetchAll();

respond(200, $result);
