import * as navbar from 'js/navbar/navbar.js';
import * as bookCardUtils from 'js/utils/book-card.js';
import * as messageUtil from "js/utils/message.js";

var windowWidth;

var searchButtonClicked = false;

var mobile = false;

$(function () {

    windowWidth = $(window).width();
    $(".banner").hide();
    // Setting the width of the page
    if (windowWidth <= 650) {
        mobile = true;
        bookCardUtils.bookCardsVisualTransition(mobile);
        navbar.addEventHandlerNavbar();
    } else {
        bookCardUtils.bookCardsVisualTransition(mobile);
        navbar.addEventHandlerNavbar();
    }

    $.ajax({
        url: "../php/feed/get.php",
        type: "get",
        success: function (response) {
            bookCardUtils.showFeed(response.message, mobile);
            if (!mobile) {
                $(".card-desktop").on("click", function (event) {
                    $(window.location).attr("href", "./book.php?isbn=" + $(this).attr('book-isbn'));
                });
            } else {
                $(".card-mobile").on("click", function (event) {
                    $(window.location).attr("href", "./book.php?isbn=" + $(this).attr('book-isbn'));
                })
            }
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true);
        }
    })

    /*************************** Eventi ***************************/

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
});

