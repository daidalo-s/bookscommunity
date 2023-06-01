export { };
import * as dateFormatter from "/tweb-progetto/js/utils/date-formatter.js";
import * as report from "/tweb-progetto/js/utils/report.js";
import * as vote from "/tweb-progetto/js/utils/vote.js";

/**
 * Function used to populate the DOM with the reviews of the given book
 * @param {*} data - JSON data from the server containing the review
 * @param {boolean} mobile - if true mobile layout, else desktop
 * @returns 
 */
export function buildReviewsCard(data, mobile) {

    if (mobile) {
        // Mobile card
        $(".review-area").append(
            $("<div>").addClass("review-mobile").append(
                $("<div>").addClass("review-rating-desktop hidden").append(
                    $("<button>").attr({ "title": "Like", "review-author": "upvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/upvote.svg")
                    ).on("click", function () {
                        vote.vote(this);
                    }),
                    $("<p>").addClass("rating").text(data.Rating),
                    $("<button>").attr({ "title": "Dislike", "review-author": "downvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/downvote.svg")
                    ).on("click", function () {
                        vote.vote(this);
                    }),
                    $("<hr>"),
                    $("<button>").attr({ "title": "segnala", "review-author": "report-" + data.Author }).append(
                        $("<img>").attr("src", "../img/report.svg")
                    ).on("click", function () {
                        report.reported(this);
                    })
                ),
                $("<div>").addClass("review-user-info-mobile").append(
                    $("<div>").addClass("review-user-info").append(
                        $("<img>").attr("src", "../img/user.svg"),
                        $("<p>").addClass("username").text(data.Author + ", " + dateFormatter.getDateFormatted(data.Date))
                    )
                ),
                $("<div>").addClass("review-title-mobile").append(
                    $("<h2>").text(data.Title),
                    $("<button>").attr({ "title": "Segnala", "review-author": "report-" + data.Author }).append(
                        $("<img>").attr("src", "../img/report.svg")
                    ).on("click", function () {
                        report.reported(this);
                    })
                ),
                $("<div>").addClass("review-content-mobile").append(
                    $("<pre>").text(data.Content)
                ),
                $("<div>").addClass("review-rating-mobile").append(
                    $("<button>").attr({ "title": "Like", "review-author": "upvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/upvote.svg"),
                    ).on("click", function () {
                        vote.vote(this);
                    }),
                    $("<p>").addClass("rating").text(data.Rating),
                    $("<button>").attr({ "title": "Dislike", "review-author": "downvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/downvote.svg")
                    ).on("click", function () {
                        vote.vote(this);
                    })
                )
            )
        )

    } else {
        // Desktop card
        $(".review-area").append(
            $("<div>").addClass("review-desktop").append(
                $("<div>").addClass("review-rating-desktop").append(
                    $("<button>").attr({ "title": "Like", "review-author": "upvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/upvote.svg")
                    ).on("click", function () {
                        vote.vote(this);
                        vote.retrieveVote(this);
                    }),
                    $("<p>").addClass("rating").text(data.Rating),
                    $("<button>").attr({ "title": "Dislike", "review-author": "downvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/downvote.svg")
                    ).on("click", function () {
                        vote.vote(this);
                    }),
                    $("<hr>"),
                    $("<button>").attr({ "title": "segnala", "review-author": "report-" + data.Author }).append(
                        $("<img>").attr("src", "../img/report.svg")
                    ).on("click", function () {
                        report.reported(this);
                    })
                ),
                $("<div>").addClass("review-user-info-desktop").append(
                    $("<div>").addClass("review-user-info").append(
                        $("<img>").attr("src", "../img/user.svg"),
                        $("<p>").addClass("username").text(data.Author + ", " + dateFormatter.getDateFormatted(data.Date))
                    )
                ),
                $("<div>").addClass("review-title-desktop").append(
                    $("<h2>").text(data.Title),
                    // Adding also the report button
                    $("<button>").attr({ "title": "Segnala", "review-author": "report-" + data.Author }).append(
                        $("<img>").attr("src", "../img/report.svg")
                    ).on("click", function () {
                        report.reported(this);
                    }).addClass("hidden")
                ),
                $("<div>").addClass("review-content-desktop").append(
                    $("<pre>").text(data.Content)
                ),
                $("<div>").addClass("review-rating-mobile hidden").append(
                    $("<button>").attr({ "title": "Like", "review-author": "upvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/upvote.svg"),
                    ).on("click", function () {
                        vote.vote(this);
                    }),
                    $("<p>").addClass("rating").text(data.Rating),
                    $("<button>").attr({ "title": "Dislike", "review-author": "downvote-" + data.Author }).append(
                        $("<img>").attr("src", "../img/downvote.svg")
                    ).on("click", function () {
                        vote.vote(this);
                    })
                )
            )
        )
        return;
    }
}

/**
 * Function that changes all the review divs inside one page and
 * changes them into a div for a mobile review
 */
export function desktopToMobileReviewTransition() {

    $('.review-desktop').each(function () {

        $(this).removeClass('review-desktop').addClass('review-mobile');
        $(this).find('.review-user-info-desktop').removeClass('review-user-info-desktop').addClass("review-user-info-mobile");
        $(this).find('.review-title-desktop').removeClass('review-title-desktop').addClass('review-title-mobile');
        $(this).find('.review-content-desktop').removeClass('review-content-desktop').addClass('review-content-mobile');
        $(this).find('.review-rating-desktop').addClass('hidden');
        $(this).find('.review-rating-mobile').removeClass('hidden');
        $(this).find('.review-title-mobile button').removeClass('hidden');
    })
}


/**
 * Function that changes all the review divs inside one page and
 * changes them into a div for a desktop review
 */
export function mobileToDesktopReviewTransition() {

    $('.review-mobile').each(function () {

        $(this).removeClass('review-mobile').addClass('review-desktop');
        $(this).find('.review-user-info-mobile').removeClass('review-user-info-mobile').addClass("review-user-info-desktop");
        $(this).find('.review-title-mobile').removeClass('review-title-mobile').addClass('review-title-desktop');
        $(this).find('.review-content-mobile').removeClass('review-content-mobile').addClass('review-content-desktop');
        $(this).find('.review-rating-mobile').addClass('hidden');
        $(this).find('.review-rating-desktop').removeClass('hidden');
        $(this).find('.review-title-desktop button').addClass('hidden');

    })
}