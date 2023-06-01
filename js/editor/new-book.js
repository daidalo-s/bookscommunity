export { }

import * as messageUtil from "/tweb-progetto/js/utils/message.js";

/**
 * Function used to modify the DOM and show the editor for the reviews
 * or the book card
 * @param {boolean} state - if true both book and review
 */
export function showEditor(state) {

    var windowWidth = $(window).width();
    var mobile = false;

    if (windowWidth <= 650) {
        mobile = true;
    }

    // Cleanup of the page, showing the editor
    if (state) {

        var oldHeader = $(".main-content-header").clone(true, true);
        var oldContent = $(".main-content-cards").clone(true, true);
        $(".main-content-header").remove();
        $(".main-content-cards").remove();

        // Showing the editor
        $.ajax({
            type: "get",
            url: "editor-template-full.html",
            success: function (response) {
                $(".main-content").append($(response));
                formFactor(mobile);
                $('html, body').animate({
                    scrollTop: 0
                }, 'fast');
                $(".back-button").on("click", function () {
                    $(".main-content-header").replaceWith(oldHeader);
                    $("form").replaceWith(oldContent);
                })
                inputValidator(state)
                $("form").on("submit", function () {
                    event.preventDefault();
                    var formData = new FormData();
                    formData.append('book-cover', $("#book-cover-field")[0].files[0]);
                    formData.append('book-title', $("#book-title").val());
                    formData.append('book-author', $("#book-author").val());
                    formData.append('genre', $("#book-genre").val());
                    formData.append('book-description', $("#book-description").val());
                    formData.append('book-year', $("#book-year").val());
                    formData.append('book-isbn-first', $("#isbn-first").val());
                    formData.append('book-isbn-second', $("#isbn-second").val());
                    formData.append('book-isbn-third', $("#isbn-third").val());
                    formData.append('book-isbn-fourth', $("#isbn-fourth").val());
                    formData.append('book-isbn-fifth', $("#isbn-fifth").val());
                    formData.append('review-title', $("#review-title").val());
                    formData.append('book-review', $("#book-review").val());

                    $.ajax({
                        type: "post",
                        url: "../php/feed/insert.php",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $(window.location).attr("href", "./book.php?isbn=" + response.message);
                        },
                        error: function (response) {
                            messageUtil.showMessage(response.responseJSON.message, true)
                        }
                    });
                })
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true)
            }
        })

        $(document).ready(function () {
            // Wait until document is fully parsed
            $("form").on('submit', function (e) {
                e.preventDefault();
            });
        })
    } else {
        var bookIsbn = { isbn: $('[book-isbn]').attr('book-isbn') };

        var report = $(".report").clone(true, true);
        var reviewArea = $(".review-area").clone(true, true);
        $(".report").remove();
        $(".review-area").remove();

        // Showing the editor
        $.ajax({
            type: "get",
            url: "editor-template-review.html",
            success: function (response) {
                $(".main-content").append($(response));
                formFactor(mobile);
                $(".back-button").on("click", function () {
                    $(".main-content-header").replaceWith(oldHeader);
                    $("form").replaceWith(oldContent);
                })
                inputValidator();

                $("form").on("submit", function () {
                    event.preventDefault();
                    $.ajax({
                        type: "post",
                        url: "../php/feed/insert.php",
                        data: $.param(bookIsbn) + "&" + $("form").serialize(),
                        success: function (response) {
                            $(window.location).attr("href", "./book.php?isbn=" + response.message);
                        },
                        error: function (response) {
                            messageUtil.showMessage(response.responseJSON.message, true);
                        }
                    });
                })
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true)
            }
        });

        $(document).ready(function () {
            // Wait until document is fully parsed
            $("form").on('submit', function (e) {
                e.preventDefault();
            });
        })
    }

    $("form").submit(function (event) {
        event.preventDefault();
    });

    $(window).on("resize", function () {
        if ($(window).width() <= 650 && !mobile) {
            mobile = true;
            // Changing the classes
            $(".form-desktop").removeClass("form-desktop").addClass("form-mobile");
            $(".book-summary-form").removeClass("book-summary-form").addClass("book-summary-form-mobile");
            $(".input-desktop").removeClass("input-desktop").addClass("input-mobile");
        } else if ($(window).width() > 650 && mobile) {
            mobile = false;
            // Chaning the classes
            $(".form-mobile").removeClass("form-mobile").addClass("form-desktop");
            $(".book-summary-form-mobile").removeClass("book-summary-form-mobile").addClass("book-summary-form");
            $(".input-mobile").removeClass("input-mobile").addClass("input-desktop");
        }

    })


}

/**
 * Function used to update the form layout depending from
 * the width of the apge
 * @param {boolean} mobile 
 */
function formFactor(mobile) {
    if (mobile) {
        // Loading the editor with mobile layout
        $(".form-desktop").removeClass("form-desktop").addClass("form-mobile");
        $(".book-summary-form").removeClass("book-summary-form").addClass("book-summary-form-mobile");
        $(".input-desktop").removeClass("input-desktop").addClass("input-mobile");
    }
}

/**
 * Function used to validate the user input
 * @param {boolean} state 
 */
function inputValidator(state) {
    // State has the same meaning of above, if true book+review
    if (state) {
        $("#book-title").on("focusout", function () {
            if ($(this).val().length > 250) {
                messageUtil.showMessage("Il titolo è troppo lungo", true);
            }
        });
        $("#book-author").on("focusout", function () {
            if ($(this).val().length > 250) {
                messageUtil.showMessage("Il nome dell'autore è troppo lungo", true);
            }
        });
        $("#book-description").on("focusout", function () {
            if ($(this).val().length > 850) {
                messageUtil.showMessage("La descrizione è troppo lunga", true);
            }
        });
        $("#book-year").on("focusout", function () {
            if (parseInt($(this).val()) < 0) {
                messageUtil.showMessage("Anno non valido", true);
            }
        });
        $("#review-title").on("focusout", function () {
            if ($(this).val().length > 60) {
                messageUtil.showMessage("Il titolo della recensione è troppo lungo", true)
            }
        });
        $("#book-review").on("focusout", function () {
            if ($(this).val().length > 3000) {
                messageUtil.showMessage("Il contenuto della recensione è troppo lungo", true);
            }
        })
    } else {
        $("#review-title").on("focusout", function () {
            if ($(this).val().length > 60) {
                messageUtil.showMessage("Il titolo della recensione è troppo lungo", true)
            }
        });
        $("#book-review").on("focusout", function () {
            if ($(this).val().length > 3000) {
                messageUtil.showMessage("Il contenuto della recensione è troppo lungo", true);
            }
        });
    }
}