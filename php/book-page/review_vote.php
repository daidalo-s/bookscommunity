<?php

include_once "../utils/respond.php";
include_once "../utils/guard_auth.php";
include_once "../utils/guard_post.php";
include_once "../../db/common.php";
include_once "../utils/add_upvote.php";
include_once "../utils/add_downvote.php";

/**
 * API route used to update the rating of one review
 */

$isbn = $_POST["isbn"];
$review_author = $_POST["author"];
$user = $_SESSION["USERNAME"];
$type = $_POST["type"];


// Checking if the user already down/upvoted the review
$db = dbConnect();

$already_liked = $db->prepare(
    "SELECT Likes.*
    FROM Likes
    WHERE Likes.ISBN = :isbn
    AND Likes.Review_author = :review_author
    AND Likes.Username = :username"
);

$already_liked->bindValue("isbn", $isbn);
$already_liked->bindValue("review_author", $review_author);
$already_liked->bindValue("username", $user);

try {
    $already_liked->execute();
} catch (PDOException $e) {
    respond(500, "Errore nella gestione della richiesta");
}

$result = $already_liked->fetchAll();

if (!empty($result)) {
    // Checking if ther's a change in the rating
    if ($result[0]["Upvote"] == "1" && $type != "Like") {
        // It was an upvote and now it's a downvote
        addDownvote($db, $isbn, $review_author, $user);
    } else if ($result[0]["Downvote"] == "1" && $type != "Dislike") {
        // It was a downvote and now it's a upvote
        addUpvote($db, $isbn, $review_author, $user);
    } else {
        // Same vote as before
        respond(409, "Valutazione già presente");
    }
}

// Newop
if ($type == "Like") {
    // Adding the upvote in the review table
    $like = $db->prepare(
        "UPDATE Review
        SET Upvote = Upvote + 1
        WHERE Review.ISBN = :isbn AND Review.Author = :review_author"
    );

    $like->bindValue("isbn", $isbn);
    $like->bindValue("review_author", $review_author);

    try {
        $like->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    // Adding the op in the operations table
    $op = $db->prepare(
        "INSERT INTO Likes(ISBN, Review_author, Username, Upvote, Downvote) VALUES (?,?,?,?,?);"
    );
    $true = true;
    try {
        $op->execute([
            $isbn, $review_author, $user, 1, 0
        ]);
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, "Like aggiunto con successo");
} else {
    // Adding the downvote in the review table
    $dislike = $db->prepare(
        "UPDATE Review
        SET Downvote = Downvote + 1
        WHERE Review.ISBN = :isbn AND Review.Author = :review_author"
    );

    $dislike->bindValue("isbn", $isbn);
    $dislike->bindValue("review_author", $review_author);

    try {
        $dislike->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    // Adding the op in the operations table
    $op = $db->prepare(
        "INSERT INTO Likes(ISBN, Review_author, Username, Upvote, Downvote) VALUES (?,?,?,?,?);"
    );

    try {
        $op->execute([
            $isbn, $review_author, $user, 0, 1
        ]);
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, "Dislike aggiunto con successo");
}
