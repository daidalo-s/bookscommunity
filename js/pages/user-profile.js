import * as navbar from "/tweb-progetto/js/navbar/navbar.js";
import * as bookCardUtils from '/tweb-progetto/js/utils/book-card.js';
import * as messageUtil from "/tweb-progetto/js/utils/message.js";
import * as pwUtil from '/tweb-progetto/js/utils/password-check.js';

var windowWidth;

// State button variable
var searchButtonClicked = false;

// if true -> mobile layout
var mobile = false;

var changePasswordClicked = false;
var deleteProfileClicked = false;

$(function () {

    windowWidth = $(window).width();
    $(".banner").hide();
    $(".change-password-form").hide();
    $(".consequences").hide();
    retrieveUserData();
    addHandlerToButtons();

    $("form").submit(function (event) {
        event.preventDefault();
    });

    // Setting the width of the page
    if (windowWidth <= 650) {
        mobile = true;
        bookCardUtils.bookCardsVisualTransition(mobile);
        navbar.addEventHandlerNavbar();
    } else {
        bookCardUtils.bookCardsVisualTransition(mobile);
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
            bookCardUtils.bookCardsVisualTransition(mobile);
        }
        if ($(window).width() > 650 && mobile) {
            mobile = false;
            bookCardUtils.bookCardsVisualTransition(mobile);
        }
    });

    /********************** Page components ***************************/

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

    $(".reviewed-books-number").on("click", function () {
        // Empting the page but copying the content
        var header = $(".main-content-header").clone(true, true);
        var content = $(".main-content-cards").clone(true, true);
        $(".main-content-header").empty();
        $(".main-content-cards").empty();
        // Adding back button
        $(".main-content-header").append(
            $("<button>").addClass("back-button").append(
                $("<img>").attr("src", "../img/back.svg")
            ).on("click", function () {
                $(".main-content-header").empty();
                $(".main-content-cards").empty();
                $(".main-content-header").replaceWith(header);
                $(".main-content-cards").replaceWith(content);
            }),
            $("<h2>").text("I libri che hai recensito:")
        )
        $.ajax({
            type: "get",
            url: "../php/user/get_all_reviews.php",
            success: function (response) {
                if (Object.keys(response.message).length === 0) {
                    $(".main-content").append(
                        $(".main-content-cards").append(
                            $("<h2>").text("Non c'è niente qui.. Aggiungi adesso un libro che hai letto!")
                        )
                    )
                } else {
                    $(".main-content").append(
                        $(".main-content-cards").append(
                            $.map(response.message, function (value, key) {
                                return bookCardUtils.buildCard(value, mobile);
                            })
                        )
                    )
                }
                if (mobile) {
                    $(".card-mobile").on("click", function () {
                        $(window.location).attr("href", "./book.php?isbn=" + $(this).attr('book-isbn'));
                    })
                } else {
                    $(".card-desktop").on("click", function () {
                        $(window.location).attr("href", "./book.php?isbn=" + $(this).attr('book-isbn'));
                    })
                }
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true)
            }
        });
    })

})

/**
 * Function that sets all the behaviour to the user personal
 * page buttons
 */
function addHandlerToButtons() {

    $("#delete-profile").on("click", function () {
        if (deleteProfileClicked) {
            deleteProfileClicked = false;
            // Already clicked, the content has to go
            $(".consequences").fadeOut();
        } else {
            deleteProfileClicked = true;
            $(".consequences").fadeIn();
        }

    })

    $("#nuke").on("click", function () {
        $.ajax({
            type: "post",
            url: "../php/user/delete_account.php",
            success: function (response) {
                $(window.location).attr("href", "./index.php");
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true);
            }
        });
    });

    $("#safe").on("click", function () {
        $(".consequences").fadeOut();
    });

    $("#change-password").on("click", function () {
        // Reset the status
        if (changePasswordClicked) {
            changePasswordClicked = false;
            $(".change-password-form").hide();
            $(".pw-not-matching").hide();
            $(".delete-profile").fadeIn();
        } else {
            changePasswordClicked = true;
            $(".delete-profile").hide();
            $(".pw-requirements").hide();
            $(".pw-not-matching").hide();
            $(".change-password-form").fadeIn();
        }
        $("#new-password").on("focus", function () {
            $(".pw-requirements").fadeIn();
        }).on("focusout", function () {
            $(".pw-requirements").fadeOut();
        })
        $("#new-password-confirmation").on("keyup", function () {
            if (!pwUtil.passwordEqual($("#new-password").val(), $("#new-password-confirmation").val())) {
                $(".pw-not-matching").fadeIn();
            } else {
                $(".pw-not-matching").fadeOut();
            }
        }).on("focusout", function () {
            $(".pw-not-matching").fadeOut();
        })
    })

    $("#submit-pw-change").on("click", function () {
        // Checking for all the fields
        if ($('#old-password').val().length > 0 &&
            $('#new-password').val().length > 0 &&
            $('#new-password-confirmation').val().length > 0) {
            if ($('#new-password').val() == $('#new-password-confirmation').val() &&
                pwUtil.passwordValid($('#new-password-confirmation').val())) {
                $.ajax({
                    type: "post",
                    url: "../php/user/update_password.php",
                    data: $(".change-password-form").serialize(),
                    success: function (response) {
                    },
                    error: function (response) {
                        messageUtil.showMessage(response.responseJSON.message, true);
                    }
                });
            } else {
                messageUtil.showMessage("La nuova password non rispetta i criteri", true)
            }
        }
        else {
            messageUtil.showMessage("Per favore, compila tutti i campi", true)
        }
    })

}

/**
 * Function used to retrieve all the user data and update the 
 * user personal page
 */
function retrieveUserData() {
    $.ajax({
        type: "get",
        url: "../php/user/user_data.php",
        success: function (response) {
            // Setting the page title
            $("head title").text("Profilo " + response.message.username);
            $(".main-content-header h1").text("Ciao, " + response.message.username);
            $(".reviewed-books-number strong").text(response.message.numbooks)
            if (response.message.upvotes >= 1) {
                $(".upvote-number strong").text(response.message.upvotes)
            } else {
                $(".upvote-number strong").text("0");
            }
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true)
        }
    });
}