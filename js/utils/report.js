export { }

import * as messageUtil from "/tweb-progetto/js/utils/message.js";

/**
 * Function that takes a page element, extract the identifier and
 * then sends it to the database
 * @param {} element 
 */
export function reported(element) {

    // Isolating the isbn
    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var isbn = urlParams.get('isbn');

    var review = element.getAttribute('book-isbn');
    if (review === "" || review === null) {
        // Isolating the username of the author
        var id = $(element).attr('review-author');
        var idParts = id.split('-');
        var username = idParts[1];
        $.ajax({
            type: "post",
            url: "../php/book-page/book_summary_report.php",
            data: { isbn: isbn, author: username },
            success: function (response) {
                messageUtil.showMessage(response.message, false)
            },
            error: function (response) {
                messageUtil.showMessage(response.responseJSON.message, true)
            }
        });
        return
    }

    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var isbn = urlParams.get('isbn');
    // Request to the server
    $.ajax({
        type: "post",
        url: "../php/book-page/book_summary_report.php",
        data: { isbn: isbn },
        success: function (response) {
            messageUtil.showMessage(response.message, false)
        },
        error: function (response) {
            messageUtil.showMessage(response.responseJSON.message, true)
        }
    });
}