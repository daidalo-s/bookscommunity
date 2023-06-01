<?php

include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_post.php";
include_once "../../db/common.php";

/**
 * API route used to report a book-card
 */

$isbn = $_POST["isbn"];
$user = $_SESSION["USERNAME"];

if (isset($_POST["author"])) {
    $review_author = $_POST["author"];

    $db = dbConnect();

    $report_already_present = null;

    $report_already_present = $db->prepare(
        "SELECT BookReviewReport.*
    FROM BookReviewReport
    WHERE BookReviewReport.ISBN = :isbn 
    AND BookReviewReport.Author = :author 
    AND BookReviewReport.Reporter = :reporter"
    );

    $report_already_present->bindValue("isbn", $isbn);
    $report_already_present->bindValue("author", $review_author);
    $report_already_present->bindValue("reporter", $user);

    try {
        $report_already_present->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    $result = $report_already_present->fetchAll();

    if (!empty($result)) {
        respond(409, "La segnalazione è già stata effettuata");
    }

    // Not already reported
    $add_report = $db->prepare(
        "INSERT INTO BookReviewReport(ISBN, Author, Reporter) VALUES (?, ?, ?);"
    );

    try {
        $add_report->execute([
            $isbn, $review_author, $user
        ]);
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, "Segnalazione ricevuta con successo. Grazie!");
} else {

    // Reporting a book-card
    $user = $_SESSION["USERNAME"];
    $db = dbConnect();

    $report_already_present = null;

    $report_already_present = $db->prepare(
        "SELECT BookCardReport.*
    FROM BookCardReport
    WHERE BookCardReport.ISBN = :isbn AND BookCardReport.Author = :author"
    );

    $report_already_present->bindValue("isbn", $isbn);
    $report_already_present->bindValue("author", $user);

    try {
        $report_already_present->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    $result = $report_already_present->fetchAll();

    if (!empty($result)) {
        respond(409, "La segnalazione è già stata effettuata");
    }

    // New report
    $add_report = $db->prepare(
        "INSERT INTO BookCardReport(ISBN, Author) VALUES (?, ?);"
    );

    try {
        $add_report->execute([
            $isbn, $user
        ]);
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, "Segnalazione ricevuta con successo. Grazie!");
}
