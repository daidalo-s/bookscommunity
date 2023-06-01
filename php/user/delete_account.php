<?php

include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_post.php";
include_once "../utils/cookie_terminator.php";

/**
 * API route used to delete a user account
 */

$username = $_SESSION["USERNAME"];

$db = dbConnect();
// Deleting all the users reviews
$delete_reviews_query = $db->prepare(
    "DELETE
    FROM Review
    WHERE Review.Author = :username"
);

$delete_reviews_query->bindValue("username", $username);

try {
    $delete_reviews_query->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

// Destroying session and cookie infos
session_unset();
session_destroy();
cookie_terminator();

$delete_user = $db->prepare(
    "DELETE
    FROM User
    WHERE User.Username = :username"
);

$delete_user->bindValue("username", $username);

try {
    $delete_user->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

header("Location: /tweb-progetto/html/index.php");

respond(200, "Richiesta completata");
