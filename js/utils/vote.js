export { }

import * as messageUtil from "/tweb-progetto/js/utils/message.js";

/**
 * Function used to handle the upvote-downvote buttons
 * @param {*} element - the button that is clicked
 */
export function vote(element) {

    // Isolating the isbn
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var isbn = urlParams.get('isbn');
    var id = $(element).attr('review-author');
    var type = $(element).attr('title');
    var idParts = id.split('-');
    var username = idParts[1];
    $.ajax({
        type: "post",
        url: "../php/book-page/review_vote.php",
        data: { isbn: isbn, author: username, type: type },
        success: function (response) {
            // Understanding if it was a like or dislike
            if (type == "Like") {
                var oldVal = parseInt($("[review-author='upvote-" + username + "']").next("p").first().text());
                $("[review-author='upvote-" + username + "']").next("p").html(oldVal + 1);
                $("[review-author='upvote-" + username + "']").addClass("button-clicked");
                $("[review-author='downvote-" + username + "']").removeClass("button-clicked");
            } else {
                var oldVal = parseInt($("[review-author='upvote-" + username + "']").next("p").first().text());
                $("[review-author='upvote-" + username + "']").next("p").html(oldVal - 1);
                $("[review-author='downvote-" + username + "']").addClass("button-clicked");
                $("[review-author='upvote-" + username + "']").removeClass("button-clicked");
            }
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true)
        }
    });
    return
}

/**
 * Function used when the pages loads to set the upvote-downvote button
 * with the right background
 * @param {*} isbn 
 */
export function retrieveVote(isbn) {

    // Retrieving all the votes
    $.ajax({
        type: "get",
        url: "../php/book-page/old_votes.php",
        data: { isbn: isbn },
        success: function (response) {
            // Finding the element in the page and updating it
            $.each(response.message, function (index, value) {
                if (value.Upvote) {
                    $("[review-author='upvote-" + value.Review_author + "']").addClass("button-clicked");
                } else {
                    $("[review-author='downvote-" + value.Review_author + "']").addClass("button-clicked");
                }
            });
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true)
        }
    });

}