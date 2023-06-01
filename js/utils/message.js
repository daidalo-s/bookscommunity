export { }

/**
 * Function used to show messages in the banner in the middle
 * of the screen
 * @param {string} message 
 * @param {boolean} error if true is an error
 */
export function showMessage(message, error) {

    // Resetting the banner
    $(".banner").removeClass("error");
    $(".banner").removeClass("success");
    $(".message").empty();

    if (error) {
        $(".banner").addClass("error");
        $(".message").html(message);
        $(".banner").fadeIn();
        setTimeout(function () {
            $(".banner").fadeOut();
        }, 2000);
    } else {
        $(".banner").addClass("success");
        $(".message").html(message);
        $(".banner").fadeIn();
        setTimeout(function () {
            $(".banner").fadeOut();
        }, 2000);

    }

}