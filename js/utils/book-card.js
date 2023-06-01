export { };

/**
 * Function used to populate the DOM of the homepage
 * @param {*} allData - the JSON retrieved from the server
 * @param {boolean} mobile - if true all the cards are in mobiel mode
 * @returns 
 */
export function showFeed(allData, mobile) {
    return $("#main").append(
        $("<div>").addClass("main-content").append(
            $("<div>").addClass("main-content-header").append(
                $("<h1>").text("Libri letti di recente")
            ),
            $("<div>").addClass("main-content-cards").append(
                $.map(allData, function (value, key) {
                    return buildCard(value, mobile);
                })
            )
        )
    );
}

/**
 * Function used to build the book-card
 * @param {*} data 
 * @param {boolean} mobile - if true the card has to be in the mobile mode
 * @returns 
 */
export function buildCard(data, mobile) {
    if (mobile) {

        // Mobile card
        return $("<div>").addClass("card-mobile").attr("book-isbn", data.ISBN).append(
            $("<h3>").addClass("title-mobile").text(data.Title),
            $("<div>").addClass("img-container-mobile").append(
                $("<img>").attr("src", "/tweb-progetto/book-covers/" + data.ISBN + ".jpg").addClass("image-mobile")
            ),
            $("<p>").addClass("genre-mobile").html("<strong>Genere:</strong> " + data.Genre),
            $("<p>").addClass("description-mobile").html("<strong>Descrizione:</strong> <pre>" + data.Description + "</pre>"),
            $("<p>").addClass("author-mobile").html("<strong>Autore:</strong> " + data.Author),
            $("<p>").addClass("year-mobile").html("<strong>Anno pubblicazione:</strong> " + data.Year),
            $("<p>").addClass("isbn-mobile").html("<strong>ISBN:</strong> " + data.ISBN)
        );
    } else {

        // Card desktop
        return $("<div>").addClass("card-desktop").attr("book-isbn", data.ISBN).append(
            $("<h3>").addClass("title-desktop").text(data.Title),
            $("<div>").addClass("img-container-desktop").append(
                $("<img>").attr("src", "/tweb-progetto/book-covers/" + data.ISBN + ".jpg").addClass("image-desktop")
            ),
            $("<p>").addClass("genre-desktop").html("<strong>Genere:</strong> " + data.Genre),
            $("<p>").addClass("description-desktop").html("<strong>Descrizione:</strong> <pre>" + data.Description + "</pre>"),
            $("<p>").addClass("author-desktop").html("<strong>Autore:</strong> " + data.Author),
            $("<p>").addClass("year-desktop").html("<strong>Anno pubblicazione:</strong> " + data.Year),
            $("<p>").addClass("isbn-desktop").html("<strong>ISBN:</strong> " + data.ISBN)
        );
    }

}


/**
     * Function that changes all the card inside the page from mobile to desktop
     * or from desktop to mobile depending from the mobile param.
     * Mobile = true -> desktop to mobile
     * Mobile = false -> mobile to desktop
     * @param {boolean} mobile 
     */
export function bookCardsVisualTransition(mobile) {

    if (mobile) {
        // Setting navbar layout
        $("#search-book").addClass("hidden-search");
        $("#search-book-bar-button").addClass("hidden-search");
        $("#search-button").removeClass("hidden-search");

        // Removing the click handler to the desktop card
        $(".card-desktop").off("click");

        // Setting the card
        $('.card-desktop').each(function () {
            $(this).removeClass('card-desktop').addClass('card-mobile');
            $(this).find('.title-desktop').removeClass('title-desktop').addClass('title-mobile');
            $(this).find('.img-container-desktop').removeClass('img-container-desktop').addClass('img-container-mobile');
            $(this).find('.genre-desktop').removeClass('genre-desktop').addClass('genre-mobile');
            $(this).find('.description-desktop').removeClass('description-desktop').addClass('description-mobile');
            $(this).find('.author-desktop').removeClass('author-desktop').addClass('author-mobile');
            $(this).find('.year-desktop').removeClass('year-desktop').addClass('year-mobile');
            $(this).find('.isbn-desktop').removeClass('isbn-desktop').addClass('isbn-mobile');
        });

        $(".card-mobile").on("click", function () {
            $(window.location).attr("href", "./book.php?isbn=" + $(this).attr('book-isbn'));
        });
    } else {
        // Setting the navbar layout
        $("#search-book").removeClass("hidden-search");
        $("#search-book-bar-button").removeClass("hidden-search");
        $("#search-button").addClass("hidden-search");

        // Removing click handler to mobile card
        $(".card-mobile").off("click");

        // Setting the cards
        $('.card-mobile').each(function () {
            $(this).removeClass('card-mobile').addClass('card-desktop');
            $(this).find('.title-mobile').removeClass('title-mobile').addClass('title-desktop');
            $(this).find('.img-container-mobile').removeClass('img-container-mobile').addClass('img-container-desktop');
            $(this).find('.genre-mobile').removeClass('genre-mobile').addClass('genre-desktop');
            $(this).find('.description-mobile').removeClass('description-mobile').addClass('description-desktop');
            $(this).find('.author-mobile').removeClass('author-mobile').addClass('author-desktop');
            $(this).find('.year-mobile').removeClass('year-mobile').addClass('year-desktop');
            $(this).find('.isbn-mobile').removeClass('isbn-mobile').addClass('isbn-desktop');
        });

        // Adding click handler
        $(".card-desktop").on("click", function () {
            $(window.location).attr("href", "./book.php?isbn=" + $(this).attr('book-isbn'));
        });
    }
}