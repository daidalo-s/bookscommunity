<?php

include_once "guard_auth.php";

/**
 * Function used to add an upvote to a selected review
 * 
 * @param $db reference to the database
 * @param $isbn isbn of the book the review is referred to
 * @param $review_author the user author of the review
 * @param $user the user that is adding the upvote
 */
function addUpvote($db, $isbn, $review_author, $user)
{
    // Making shure that the upvote is removed
    $del_upvote = $db->prepare(
        "UPDATE Likes
        SET Downvote = 0, Upvote = 1
        WHERE Likes.ISBN = :isbn
        AND Likes.Review_author = :review_author
        AND Likes.Username = :user"
    );

    $del_upvote->bindValue("isbn", $isbn);
    $del_upvote->bindValue("review_author", $review_author);
    $del_upvote->bindValue("user", $user);

    try {
        $del_upvote->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    // Adding the upvote in the Review table
    $like = $db->prepare(
        "UPDATE Review
        SET Upvote = Upvote + 1, Downvote = Downvote - 1
        WHERE Review.ISBN = :isbn AND Review.Author = :review_author"
    );

    $like->bindValue("isbn", $isbn);
    $like->bindValue("review_author", $review_author);

    try {
        $like->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, "Upvote aggiunto con successo");
}
