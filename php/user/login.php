<?php

include_once "../utils/guard_post.php";
include_once "../../db/common.php";
include_once "../utils/respond.php";
include_once "../utils/validate.php";

/**
 * API route to login a user.
 */


/**
 * This constant sets the expiration date of the PHPSESSID cookie.
 * It's currently set to 60*60*24*7 = one week
 */
$TIMEOUT_SESSION = 604800;

if (isset($_SESSION["username"])) {
    respond(200, "Utente già loggato.");
}

if (!isset($_POST["username"]) || !isset($_POST["password"])) {
    respond(400, "Campo di login mancante.");
}

$processed_username = strtolower($_POST["username"]);

// Check with username requirements.
validateUsername($processed_username);

$db = dbConnect();

$query_user = $db->prepare(
    "SELECT username, password, admin FROM User WHERE username = ?;"
);

try {
    $query_user->execute([$processed_username]);
} catch (PDOException $e) {
    respond(500, "Errore nell'elaborazione della richiesta.");
}

// Check if user is present in database.
if ($query_user->rowCount() == 0) {
    respond(404, "Utente non trovato.");
}

$user = $query_user->fetch();

// Start session if not already started.
if (!isset($_SESSION)) {
    session_start();
}

if (md5($_POST["password"]) == $user["password"]) {
    $_SESSION["USERNAME"] = $processed_username;
    $_SESSION["ADMIN"] = boolval($user["admin"]);
    $_SESSION["LAST_TIME"] = time();
    setcookie("PHPSESSID", session_id(), time() + $TIMEOUT_SESSION, "/");
    if ($_SESSION["ADMIN"] == true) {
        respond(200, "admin-home.php");
    }
    respond(200, "home.php");
}

respond(401, "Username o password non corretti.");
