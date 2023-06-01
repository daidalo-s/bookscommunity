export { };

import * as messageUtil from "/tweb-progetto/js/utils/message.js";
import * as bookCardBuilder from "/tweb-progetto/js/utils/book-card.js";
import * as editor from "/tweb-progetto/js/editor/new-book.js";

/**
 * Function that adds behaviour to the buttons in the navbar
 */
export function addEventHandlerNavbar() {

    var newButtonClicked = false;

    $("#logo").on("click", function () {
        $(window.location).attr("href", "./home.php");
    })

    $("#search-book-bar-button").on("click", function () {
        // Getting the value of the text field, send it to the server,
        // on success clean the page and showing the content
        $.ajax({
            type: "get",
            url: "../php/search/get.php",
            data: { "keyword": $("#search-book").val() },
            success: function (response) {
                // Cleaning the page, changing the header and updating the DOM
                // with the results of the search
                $(".main-content-header").empty();
                $(".main-content-cards").empty();

                $(".main-content-header").append(
                    $("<h2>").text("Il risultato della ricerca: " + $("#search-book").val())
                )

                $(".main-content-cards").append(
                    // Calling the function that creates cards
                    $.map(response.message, function (value, key) {
                        return bookCardBuilder.buildCard(value, false);
                    })
                )
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true);
            }
        });
    })

    $("#search-book-slide-down-button").on("click", function () {
        $.ajax({
            type: "get",
            url: "../php/search/get.php",
            data: { "keyword": $("#search-book-slide-down").val() },
            success: function (response) {
                // Cleaning the page, changing the header and updating the DOM
                // with the results of the search
                $(".main-content-header").empty();
                $(".main-content-cards").empty();

                // Hiding the navbar
                $(".hidden-search-bar").slideUp(300);
                $("#main").animate({
                    marginTop: "0px"
                }, "medium");

                $(".main-content-header").append(
                    $("<h2>").text("Il risultato della ricerca: " + $("#search-book-slide-down").val())
                )

                $(".main-content-cards").append(
                    // Calling the function that creates cards
                    $.map(response.message, function (value, key) {
                        return bookCardBuilder.buildCard(value, true);
                    })
                )
                $(".main-content-cards div").on("click", function () {
                    $(window.location).attr("href", "./book.php?isbn=" + $(this).attr('book-isbn'));
                })
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true);
            }
        });
    })

    $("#new-book").on("click", function () {
        if ($("#new-book").attr("title") == "Aggiungi un libro") {
            if (!newButtonClicked) {
                newButtonClicked = true;
                editor.showEditor(true);
                setTimeout(function () {
                    var posizione = $("form").offset().top;
                    $("html, body").scrollTop(posizione);
                }, 100);
            }
        } else {
            if (!newButtonClicked) {
                newButtonClicked = true;
                editor.showEditor(false);
                setTimeout(function () {
                    var posizione = $("form").offset().top;
                    $("html, body").scrollTop(posizione);
                }, 100);
            }
        }
    })

    $("#random-book").on("click", function () {
        $.ajax({
            type: "get",
            url: "../php/book-page/rand_isbn.php",
            success: function (response) {
                $(window.location).attr("href", "./book.php?isbn=" + response.message[0].ISBN);
            },
            error: function (response) {
                var error = JSON.parse(response.responseText);
                messageUtil.showMessage(error.message, true);
            }
        });
    })

    $("#personal-profile").on("click", function () {
        $(window.location).attr("href", "./user.php");
    })


    $("#logout").on("click", function () {
        $.ajax({
            url: "../php/user/logout.php",
            type: "post",
            success: function (response) {
                $(window.location).attr("href", "./" + response.message);
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true);
            }
        })
    })
}

