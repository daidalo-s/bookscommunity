import * as bookCardUtils from '/tweb-progetto/js/utils/book-card.js';
import * as messageUtil from "/tweb-progetto/js/utils/message.js";

$(function () {

    populateStats();

    // Adding handler to the two buttons
    $("#book-reports").on("click", function () {
        var oldHeader = $(".header").clone(true, true);
        var oldContent = $(".card-area").clone(true, true);
        populateReportedBooks(oldHeader, oldContent);
    })
    $("#review-reports").on("click", function () {
        var header = $(".header").clone(true, true);
        var content = $(".card-area").clone(true, true);
        populateReportedReviews(header, content);
    })
    $("#logout").on("click", function () {
        $.ajax({
            type: "post",
            url: "../php/user/logout.php",
            success: function (response) {
                $(window.location).attr("href", "./" + response.message);
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true);
            }
        });
    })
})

/**
 * Function used to update the admin stats in the admin panel
 */
function populateStats() {

    // Loading the stats
    $.ajax({
        type: "get",
        url: "../php/admin/get-stats.php",
        success: function (response) {
            $("#username").append(response.message.username);
            $("#post-number strong").text(response.message.booksNumber);
            $("#review-number strong").text(response.message.reviewNumber);
            $("#like-number strong").text(response.message.upvoteTotal);
            $("#user-number strong").text(response.message.userTotal);
            $("#book-reports strong").text(response.message.reportedBookTotal);
            $("#review-reports strong").text(response.message.reportedReviewTotal);
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true);
        }
    });
}

/**
 * Function used to populate the reported books section in the admin panel
 * @param {*} oldHeader 
 * @param {*} oldContent 
 */
function populateReportedBooks(oldHeader, oldContent) {

    var oldHeaderCopy = oldHeader;
    var oldContentCopy = oldContent;

    var bookTitle;
    var bookIsbn;
    var requestParam;
    var $this;

    $(".header h2").replaceWith(
        $("<div>").addClass("reported-book-title").append(
            $("<button>").append(
                $("<img>").attr("src", "../img/back.svg")
            ),
            $("<span>").addClass("spacer"),
            $("<h2>").text("I libri segnalati:")
        )
    );

    getReportedBooks(function (books) {
        $(".card-area").replaceWith($("<div>").addClass("cards").append(books));
    });

    $("button").on("click", function () {
        $(".header").replaceWith(oldHeaderCopy);
        $(".cards").replaceWith(oldContentCopy);
        populateStats();
    });

    $(document).on("click", ".reported-book", function () {
        $this = $(this);
        // We need the ISBN for the server request
        var clicked = $(this).data("clicked");
        if (!clicked) {

            $(this).data("clicked", true);
            $(this).addClass("clicked");
            bookTitle = $(this).find("h2").clone();
            bookIsbn = $(this).find("p").clone();
            requestParam = $(this).find("p").attr("book-isbn");
            $(this).find("h2").remove();
            $(this).find("p").remove();

            $.ajax({
                type: "get",
                url: "./admin/template-book.html",
                success: function (response) {
                    $this.append(response);
                    $(".edit-form").on("click", function () {
                        event.stopPropagation();
                    });
                    // Getting the form data
                    $.ajax({
                        type: "get",
                        url: "../php/admin/get_rep_book_card.php",
                        data: { isbn: requestParam },
                        success: function (response) {
                            // updating the form fields
                            $this.find("#book-title").attr("value", response.message[0].Title);
                            $this.find("#book-isbn").attr("value", response.message[0].ISBN);
                            $this.find("#book-author").attr("value", response.message[0].Author);
                            $this.find("#book-description").val(response.message[0].Description);
                            $this.find("#book-year").attr("value", response.message[0].Year);
                            $this.find("#book-genre").val(response.message[0].Genre)

                            // Adding event handler to the two buttons
                            $("#confirm").on("click", function () {
                                event.stopPropagation();
                                event.preventDefault();
                                $.ajax({
                                    type: "post",
                                    url: "../php/admin/update_book_card.php",
                                    data: $("form").serialize() + "&old-isbn=" + requestParam,
                                    success: function (response) {
                                        messageUtil.showMessage(response.message, false);
                                        $this.remove();
                                    },
                                    error: function (response) {
                                        messageUtil.showMessage(response.responseJSON.message, true);
                                    }
                                });
                            });
                            $("#undo").on("click", function () {
                                event.stopPropagation();
                                event.preventDefault();
                                $this.empty();
                                $this.append(bookTitle, bookIsbn);
                                $this.data("clicked", false);
                                $this.removeClass("clicked");
                            })
                        },
                        error: function (response) {
                            messageUtil.showMessage(response.responseJSON.message, true);
                        }
                    });
                },
                error: function (response) {
                    messageUtil.showMessage(response.responseJSON.message, true);
                }
            });
        } else {
            $(this).data("clicked", false);
            $(this).removeClass("clicked");
            $(this).find(".edit-form").remove(".edit-form");
            $(this).find(".buttons").remove(".buttons");
            $(this).append(bookTitle, bookIsbn);
        }
    });

}

/**
 * Function used to retrieve all the reported book informations
 * @param {*} callback 
 */
function getReportedBooks(callback) {
    $.ajax({
        type: "get",
        url: "../php/admin/get_rep_books.php",
        success: function (response) {
            var books = $.map(response.message, function (value, key) {
                return $("<div>").addClass("reported-book").append(
                    $("<h2>").text(value.Title),
                    $("<p>").attr("book-isbn", value.ISBN).text("ISBN: " + value.ISBN)
                )
            });
            callback(books);
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true);
        }
    });
}

/**
 * Function used to populate the list of reported reviews
 * @param {*} oldHeader 
 * @param {*} oldContent 
 */
function populateReportedReviews(oldHeader, oldContent) {
    var oldHeaderCopy = oldHeader;
    var oldContentCopy = oldContent;

    $(".header h2").replaceWith(
        $("<div>").addClass("reported-review-title").append(
            $("<button>").append(
                $("<img>").attr("src", "../img/back.svg")
            ),
            $("<span>").addClass("spacer"),
            $("<h2>").text("Le recensioni segnalate:")
        )
    );

    getReportedReviews(function (reviews) {
        $(".card-area").replaceWith($("<div>").addClass("cards").append(reviews));
    });

    $("button").on("click", function () {
        $(".header").replaceWith(oldHeaderCopy);
        $(".cards").replaceWith(oldContentCopy);
        populateStats();
    });

    $(document).on("click", ".reported-review", function () {
        var clicked = $(this).data("clicked");

        if (!clicked) {
            // Setting the flag to true
            $(this).data("clicked", true);
            $(this).addClass("clicked");
            // Saving the reference to the paragraph i want to update
            var $reviewContent = $("<p>").attr("id", "review-content");
            $(this).append(
                $reviewContent,
                $("<div>").addClass("rep-rev-buttons").append(
                    $("<button>").attr("id", "save-review").text("Approva recensione").on("click", function () {
                        event.stopPropagation();
                        let isbn = $(this).closest('div').parent().find('p').attr('book-isbn');
                        let author = $(this).closest('div').parent().find('p').attr('author');
                        $.ajax({
                            type: "post",
                            url: "../php/admin/review-report-save.php",
                            data: { isbn: isbn, author: author },
                            success: function (response) {
                                messageUtil.showMessage(response.message, false);
                                $(".cards").empty();
                                getReportedReviews(function (reviews) {
                                    $(".cards").append(reviews);
                                });
                            },
                            error: function (response) {
                                messageUtil.showMessage(response.responseJSON.message, true);
                            }
                        });
                    }),
                    $("<button>").attr("id", "delete-review").text("Elimina la recensione").on("click", function () {
                        event.stopPropagation();
                        let isbn = $(this).closest('div').parent().find('p').attr('book-isbn');
                        let author = $(this).closest('div').parent().find('p').attr('author');
                        $.ajax({
                            type: "post",
                            url: "../php/admin/review-report-del.php",
                            data: { isbn: isbn, author: author },
                            success: function (response) {
                                $(".cards").empty();
                                getReportedReviews(function (reviews) {
                                    $(".cards").append(reviews);
                                });
                            },
                            error: function (response) {
                                messageUtil.showMessage(response.responseJSON.message, true);
                            }
                        });
                    })
                )
            );

            // Getting and updating the paragraph content
            $.ajax({
                type: "get",
                url: "../php/admin/get_rep_review_content.php",
                data: { isbn: $(this).find("p").attr("book-isbn"), author: $(this).find("p").attr("author") },
            }).done(function (response) {
                $reviewContent.text(response.message[0].Content);
            }).fail(function (response) {
                messageUtil.showMessage(response.responseJSON.message, true);
            });
        } else {
            $(this).data("clicked", false);
            $(this).removeClass("clicked");
            $(this).find(".rep-rev-buttons").remove();
            $(this).find("#review-content").remove();
            $(this).find("#save-review").remove();
            $(this).find("#delete-review").remove();
        }
    });

}

/**
 * Function used to retrieve the reported review content
 * @param {*} callback 
 */
function getReportedReviews(callback) {
    $.ajax({
        type: "get",
        url: "../php/admin/get_rep_reviews.php",
        success: function (response) {
            var reviews = $.map(response.message, function (value, key) {
                return $("<div>").addClass("reported-review").append(
                    $("<h2>").text(value.Title),
                    $("<p>").attr({ "book-isbn": value.ISBN, "author": value.Author }).text("Autore: " + value.Author)
                )
            });
            callback(reviews);
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true);
        }
    });
}