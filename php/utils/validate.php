<?php

include_once "respond.php";

/**
 * Collection of functions used to validate user-input.
 */

/**
 * Function to validate username.
 *
 * A username is valid when all these conditions are met:
 *  - length between 2 and 20 charaters.
 *  - no special symbols used.
 *
 * @param string $username The username to validate.
 */
function validateUsername($username)
{
    $valid =
        strlen($username) > 2 &&
        strlen($username) <= 20 &&
        ctype_alnum($username);

    $ERROR_MSG =
        "Il nome utente deve essere lungo tra 2 e 20 caratteri e non deve contenere caratteri speciali";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate password.
 *
 * A password is valid when all these conditions are met:
 *  - length of at least 8 characters.
 *  - special symbols used.
 *
 * @param string $password The password to validate.
 */
function validatePassword($password)
{
    $valid = strlen($password) >= 8 && !ctype_alnum($password);

    $ERROR_MSG =
        "La password deve essere lunga almeno 8 caratteri e contenere almeno un carattere speciale";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate review title.
 * 
 * A review title is valid if it's length is less than 60 characters.
 * 
 * @param string $title The title to validate
 */
function validateReviewTitle($title)
{
    $valid = strlen($title) <= 60;

    $ERROR_MSG = "Il titolo della recensione deve essere lungo non più di 60 caratteri";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate review content.
 * 
 * A review content is valid if it's length is less than 3000 characters.
 * 
 * @param string $content The content to validate
 */
function validateReviewContent($content)
{
    $valid = strlen($content) <= 3000;

    $ERROR_MSG = "La recensione non deve essere più lunga di 3000 caratteri";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate book title .
 * 
 * A book title is valid if it's length is less than 250 characters.
 * 
 * @param string $title The title to validate
 */
function validateBookTitle($title)
{
    $valid = strlen($title) <= 250;

    $ERROR_MSG = "Il titolo del libro non può superare i 250 caratteri";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate the book author's name.
 * 
 * A book author's name is valid if it's length is less than 250 characters.
 * 
 * @param string $author The author's name to validate
 */
function validateBookAuthor($author)
{
    $valid = strlen($author) <= 250;

    $ERROR_MSG = "Il nome dell'autore non può essere più lungo di 250 caratteri";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate book genre.
 * 
 * The book genre is valid if it's one of the allowed genres
 * (Romanzo, Fantascienza, etc).
 * 
 * @param string $genre The genre to validate
 */
function validateBookGenre($genre)
{
    $valid = $genre == "Romanzo" || $genre == "Fantascienza" || $genre == "Fantasy" || $genre == "Giallo" || $genre == "Biografia" || $genre == "Divulgativo" || $genre == "Poesia" || $genre == "Teatro";

    $ERROR_MSG = "Il genere inserito non è tra quelli validi";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate book description.
 * 
 * A book description is valid if it's length is less than 850 characters.
 * 
 * @param string $description The description to validate
 */
function validateBookDescription($description)
{
    $valid = strlen($description) <= 850;

    $ERROR_MSG = "La descrizione non deve essere più lunga di 850 caratteri";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate book's year.
 * 
 * A book's year is valid if it's inside the range 0 - present year
 * 
 * @param string $yaer The year to validate
 */
function validateBookYear($year)
{
    $valid = (int)$year >= 0 && (int)$year <= (int)date("Y");

    $ERROR_MSG = "L'anno di pubblicazione inserito non è valido";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}

/**
 * Function to validate book isbn.
 * 
 * A book ISBN is valid if all of it's part are != null & are only composed
 * by digits.
 * 
 * @param string $first_part The first part of the isbn to validate
 * @param string $second_part The second part of the isbn to validate
 * @param string $third_part The third part of the isbn to validate
 * @param string $fourth_part The fourth part of the isbn to validate
 * @param string $fifth_part The fifth part of the isbn to validate
 */
function validateBookISBN($first_part, $second_part, $third_part, $fourth_part, $fifth_part)
{
    $valid = ($first_part != null && ctype_digit($first_part)) &&
        ($second_part != null && ctype_digit($second_part)) &&
        ($third_part != null && ctype_digit($third_part)) &&
        ($fourth_part != null && ctype_digit($fourth_part)) &&
        ($fifth_part != null && ctype_digit($fifth_part));

    $ERROR_MSG = "L'ISBN inserito non è in un formato valido";

    if (!$valid) {
        respond_not_valid($ERROR_MSG);
    }
}
