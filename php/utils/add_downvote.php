<?php

include_once "guard_auth.php";

/**
 * Function used to add one downvote to a selected review
 * 
 * @param $db reference to the database
 * @param $isbn isbn of the book the review is referred to
 * @param $review_author the user author of the review
 * @param $user the user that is adding the upvote
 */
function addDownvote($db, $isbn, $review_author, $user)
{
    // Making shure the upvote is removed
    $del_upvote = $db->prepare(
        "UPDATE Likes
        SET Downvote = 1, Upvote = 0
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

    // Adding the donwvote in the Review table
    $dislike = $db->prepare(
        "UPDATE Review
        SET Downvote = Downvote + 1, Upvote = Upvote - 1
        WHERE Review.ISBN = :isbn AND Review.Author = :review_author"
    );

    $dislike->bindValue("isbn", $isbn);
    $dislike->bindValue("review_author", $review_author);

    try {
        $dislike->execute();
    } catch (PDOException $e) {
        respond(500, "Errore nella gestione della richiesta");
    }

    respond(200, "Downvote aggiunto con successo");
}
