<?php


include_once "../utils/respond.php";
include_once "../utils/validate.php";
include_once "../../db/common.php";
include_once "../utils/guard_post.php";

/**
 * API route to signup a user.
 */


// Request params check
if (!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["pwconfirmation"])) {
    respond(400, "Uno o più campi del form mancanti.");
}

validateUsername($_POST["username"]);
validatePassword($_POST["password"]);

// Check if password and password_check are the same.
if ($_POST["password"] != $_POST["pwconfirmation"]) {
    respond_not_valid("Le password devono combaciare.");
}

$db = dbConnect();

$lowercase_username = strtolower($_POST["username"]);

// Hash password 
$passwrd_hash = md5($_POST["password"]);

// Get users from database.
$user_query = $db->prepare("SELECT username FROM User WHERE username = ?;");

try {
    $user_query->execute([$lowercase_username]);
} catch (PDOException $e) {
    respond(500, "Errore durante l'elaborazione della richiesta");
}

// Check if user already present in database.
if ($user_query->rowCount() > 0) {
    respond(409, "Username già in uso.");
}

// We can now add the new user to the database.
$store_user = $db->prepare(
    "INSERT INTO User(username, password) VALUES (?, ?);"
);
try {
    $store_user->execute([
        $lowercase_username,
        $passwrd_hash
    ]);
} catch (PDOException $e) {
    respond(500, "Errore nel salvataggio del nuovo utente");
}

session_start();

$_SESSION["USERNAME"] = $_POST["username"];
$_SESSION["ADMIN"] = false;
$_SESSION["LAST_TIME"] = time();

setcookie("session_id", session_id(), 0, "/");

respond(200, "home.php");
