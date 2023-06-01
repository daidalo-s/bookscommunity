<?php

include_once "../../db/common.php";
include_once "../utils/guard_admin.php";
include_once "../utils/respond.php";
include_once "../utils/guard_post.php";

/**
 * API route used to update the book's info from the admin
 * page after a user report.
 */


define("PARAMS", ["book-author", "book-description", "book-isbn", "book-title", "book-year", "genre", "old-isbn"]);

// Checking all the required params are there
foreach (PARAMS as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        respond(400, "Il campo \"$field\" non è presente.");
    }
}

$db = dbConnect();

$update_query = $db->prepare(
    "UPDATE Book
    SET Book.Author = :author, Book.Description = :description, 
    Book.ISBN = :isbn, Book.Title = :title, Book.Year = :year, 
    Book.Genre = :genre
    WHERE Book.ISBN = :oldIsbn"
);

$update_query->bindValue("author", $_POST["book-author"]);
$update_query->bindValue("description", $_POST["book-description"]);
$update_query->bindValue("isbn", $_POST["book-isbn"]);
$update_query->bindValue("title", $_POST["book-title"]);
$update_query->bindValue("year", $_POST["book-year"]);
$update_query->bindValue("genre", $_POST["genre"]);
$update_query->bindValue("oldIsbn", $_POST["old-isbn"]);

try {
    $update_query->execute();
    if ($update_query->rowCount() == 0) {
        respond(400, "Nessuna riga è stata aggiornata. Controllare che l'ISBN sia corretto.");
    }
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

// Changing the image name
if ($_POST["book-isbn"] != $_POST["old-isbn"]) {
    // Defining the old name to search and the new name to assing
    $old_cover_name = $_POST["old-isbn"] . ".jpg";
    $new_file_name = $_POST["book-isbn"] . ".jpg";

    // Book covers folder path
    $folder_path = "../../book-covers/";

    // Searching the file in the folder
    $file_path = glob($folder_path . $old_cover_name);

    // Checking if the file is found
    if ($file_path) {
        // Renaming the file
        if (!rename($file_path[0], $folder_path . $new_file_name)) {
            respond(500, "Errore nella gestione della richiesta");
        }
    } else {
        respond(500, "Errore nella gestione della richiesta");
    }
}
// Deleting the report
$delete_report_query = $db->prepare(
    "DELETE FROM BookCardReport
    WHERE BookCardReport.ISBN = :isbn"
);

$delete_report_query->bindValue("isbn", $_POST["book-isbn"]);

try {
    $delete_report_query->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

respond(200, "Form salvato con successo");
