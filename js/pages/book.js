import * as book from "/tweb-progetto/js/utils/book-card.js";
import * as review from "/tweb-progetto/js/utils/book-review.js";
import * as navbar from "/tweb-progetto/js/navbar/navbar.js";
import * as messageUtil from "/tweb-progetto/js/utils/message.js";
import * as reportUtil from "/tweb-progetto/js/utils/report.js";
import * as vote from "/tweb-progetto/js/utils/vote.js";

var windowWidth;

var searchButtonClicked = false;

var mobile = false;

$(function () {

    windowWidth = $(window).width();
    $(".banner").hide();

    // Chaning button's titile
    $("#new-book").attr("title", "Aggiungi una recensione");

    // Setting the width of the page
    if (windowWidth <= 650) {
        mobile = true;
        book.bookCardsVisualTransition(mobile);
        navbar.addEventHandlerNavbar();
    } else {
        book.bookCardsVisualTransition(mobile);
        navbar.addEventHandlerNavbar();
    }

    $(window).resize(function () {

        if ($(window).width() >= 650 && searchButtonClicked == true) {
            $(".hidden-search-bar").slideUp(300);
            searchButtonClicked = false;
            $("#main").animate({
                marginTop: "60px"
            }, "slow");
        }
        if ($(window).width() <= 650 && !mobile) {
            mobile = true;
            book.bookCardsVisualTransition(mobile);
            review.desktopToMobileReviewTransition();
        }
        if ($(window).width() > 650 && mobile) {
            mobile = false;
            book.bookCardsVisualTransition(mobile);
            review.mobileToDesktopReviewTransition();
        }
    });

    $("#search-button").on("click", function () {
        if (!searchButtonClicked) {
            $("#main").animate({
                marginTop: "120px"
            }, "medium");
            $(".hidden-search-bar").slideDown(300);
            searchButtonClicked = true;
        } else {
            $(".hidden-search-bar").slideUp(300);
            $("#main").animate({
                marginTop: "60px"
            }, "medium");
            searchButtonClicked = false;
        }
    });

    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var isbn = urlParams.get('isbn');

    $.ajax({
        type: "get",
        url: "../php/book-page/get.php",
        data: { isbn: isbn },
        success: function (response) {
            // The first key of the JSON is the book data, from the second all
            // reviews
            var bookData = response.message.shift();

            // Chaning page title
            $("head title").text(bookData.Title);

            book.buildCard(bookData, mobile)
            $("#main").append($("<div>").addClass("main-content review-visual").append(
                $("<div>").html(book.buildCard(bookData, mobile))
            ).append(
                $("<div>").addClass("report").html("<p id='report-book-summary' "
                    + "book-isbn='" + bookData.ISBN + "'"
                    + "style='text-decoration: underline; font-style: italic'>"
                    + "Qualcosa non va nella scheda del libro? Segnalalo!</p>")));
            $(".review-visual").append(
                $("<div>").addClass("review-area").append(
                    $("<h1>").text("Le recensioni dei nostri utenti:")));
            $(".review-visual").append(
                $.map(response.message, function (value, key) {
                    return review.buildReviewsCard(value, mobile);
                })
            )
            // Retrieving votes
            if (mobile) {
                vote.retrieveVote($(".card-mobile").attr("book-isbn"));
            } else {
                vote.retrieveVote($(".card-desktop").attr("book-isbn"));
            }
            // Adding event to the report button
            $("#report-book-summary").on("click", function () {
                reportUtil.reported(this);
            })
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true);
        }
    }

    );
})
