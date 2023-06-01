<?php

include_once "../../db/common.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_post.php";
include_once "../utils/respond.php";
include_once "../utils/validate.php";

/**
 * API route used to insert either a review of a book
 * or the book-card + the user's review of the book
 */

$db = dbConnect();

// Understanding if it's only a review or the full review+book-card
if (isset($_POST["book-title"])) {
    // It's both
    // Book's card part
    $book_title = htmlspecialchars($_POST["book-title"]);
    validateBookTitle($book_title);

    $book_author = htmlspecialchars($_POST["book-author"]);
    validateBookAuthor($book_author);

    $book_genre = htmlspecialchars($_POST["genre"]);
    validateBookGenre($book_genre);

    $book_description = htmlspecialchars($_POST["book-description"]);
    validateBookDescription($book_description);

    $book_year = (int)$_POST["book-year"];
    validateBookYear($book_year);

    // ISBN's five parts
    $book_isbn_first = $_POST["book-isbn-first"];
    $book_isbn_second = $_POST["book-isbn-second"];
    $book_isbn_third = $_POST["book-isbn-third"];
    $book_isbn_fourth = $_POST["book-isbn-fourth"];
    $book_isbn_fifth = $_POST["book-isbn-fifth"];
    validateBookISBN($book_isbn_first, $book_isbn_second, $book_isbn_third, $book_isbn_fourth, $book_isbn_fifth);
    $isbn_full = $book_isbn_first . "-" . $book_isbn_second . "-" . $book_isbn_third . "-" . $book_isbn_fourth . "-" . $book_isbn_fifth;

    $isbn_already_present = $db->prepare(
        "SELECT Book.ISBN
        FROM Book
        WHERE Book.ISBN = :isbn"
    );

    $isbn_already_present->bindValue("isbn", $isbn_full);

    try {
        $isbn_already_present->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    if ($isbn_already_present->rowCount() != 0) {
        respond(409, "ISBN già presente");
    }

    // Checking the uploaded file
    if (isset($_FILES['book-cover']['name']) && $_FILES['book-cover']['error'] == 0) {
        $allowed_extensions = ['jpg'];
        $file_info = pathinfo($_FILES['book-cover']['name']);
        $file_extension = strtolower($file_info['extension']);

        // Checking if the uploaded file extension is admitted
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = $isbn_full . "." . $file_extension;
            $destination = '../../book-covers/' . $new_filename;
            if (!move_uploaded_file($_FILES['book-cover']['tmp_name'], $destination)) {
                respond(500, "Errore nel caricamento del file");
            }
        } else {
            respond(400, "Il formato dell'immagine non è supportato");
        }
    } else {
        respond(400, "Immagine non presente");
    }
    // Ready to insert
    $insert_new_book_info = $db->prepare(
        "INSERT INTO Book (ISBN, Title, Genre, Description, Author, Year, Adder_user)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    try {
        $insert_new_book_info->execute([
            $isbn_full, $book_title, $book_genre, $book_description, $book_author, $book_year, $_SESSION["USERNAME"]
        ]);
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    // review part
    $review_title = htmlspecialchars($_POST["review-title"]);
    validateReviewTitle($review_title);
    $review_content = htmlspecialchars($_POST["book-review"]);
    validateReviewContent($review_content);

    $review_insert_query = $db->prepare(
        "INSERT INTO Review (Author, Content, ISBN, Title)
        VALUES (?, ?, ?, ?);"
    );

    try {
        $review_insert_query->execute([
            $_SESSION["USERNAME"],
            $review_content,
            $isbn_full,
            $review_title
        ]);
    } catch (PDOException $e) {
        // We failed, we must delete the previous insert
        $del_inserted_book = $db->prepare(
            "DELETE FROM Book
            WHERE Book.ISBN = :isbn"
        );
        $del_inserted_book->bindValue("isbn", $isbn_full);
        $del_inserted_book->execute();
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, $isbn_full);
} else {
    // Just a review
    $book_isbn = $_POST["isbn"];
    $title = htmlspecialchars($_POST["review-title"]);
    validateReviewTitle($title);
    $content = htmlspecialchars($_POST["book-review"]);
    validateReviewContent($content);

    // Cheking the ISBN is valid
    $book_isbn_valid = $db->prepare(
        "SELECT Book.ISBN
        FROM Book
        WHERE Book.ISBN = :isbn_param"
    );

    $book_isbn_valid->bindValue("isbn_param", $book_isbn);

    try {
        $book_isbn_valid->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    if ($book_isbn_valid->rowCount() == 0) {
        respond(404, "Libro non trovato");
    }

    $new_review_insert = $db->prepare(
        "INSERT INTO Review (ISBN, Author, Title, Content)
        VALUES (?, ?, ?, ?);"
    );

    try {
        $new_review_insert->execute(
            [
                $book_isbn,
                $_SESSION["USERNAME"],
                $title,
                $content
            ]
        );
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, $book_isbn);
}
