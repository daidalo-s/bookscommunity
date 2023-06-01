<?php

include_once "../utils/respond.php";
include_once "../utils/guard_post.php";
include_once "../utils/guard_auth.php";
include_once "../../db/common.php";
include_once "../utils/validate.php";

/**
 * API route used to update the user password
 * 
 */

$old_pw = $_POST["old-pw"];
$new_pw = $_POST["new-pw"];
$new_pw_confirmation = $_POST["new-pw-confirmation"];

// Params check
if ($old_pw == null || $new_pw == null || $new_pw_confirmation == null) {
    respond(400, "Campi mancanti");
}

if (!($new_pw == $new_pw_confirmation)) {
    respond(400, "Le due password non corrispondono");
}

validatePassword($new_pw);

// Old password check
$db = dbConnect();

$old_password_from_db = $db->prepare(
    "SELECT User.password
    FROM User
    WHERE User.username = :username"
);

$old_password_from_db->bindValue("username", $_SESSION["USERNAME"]);

try {
    $old_password_from_db->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$tmp = $old_password_from_db->fetchAll();
$old_password_md5 = $tmp[0]["password"];

if (md5($old_pw) != $old_password_md5) {
    respond(400, "La vecchia password non è corretta");
} else {

    // Making shure the new password is different from the new one
    if (md5($new_pw) == $old_password_md5) {
        respond(400, "La nuova password non può essere uguale alla vecchia");
    }

    $new_pw_update = $db->prepare(
        "UPDATE User
        SET password = :new_password
        WHERE User.username = :username"
    );

    $new_pw_update->bindValue("new_password", md5($new_pw));
    $new_pw_update->bindValue("username", $_SESSION["USERNAME"]);

    try {
        $new_pw_update->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }
}

respond(200, "Password aggiornata con successo");
