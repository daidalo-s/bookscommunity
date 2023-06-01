export { };

/**
 * Function used to format the date in the review card
 * @param {*} date 
 * @returns 
 */
export function getDateFormatted(date) {
    // Input: date with the format "2023-03-29"
    var dateObj = new Date(date);
    var day = dateObj.getDate();
    var monthIndex = dateObj.getMonth();
    var year = dateObj.getFullYear();
    var months = [
        "gennaio",
        "febbraio",
        "marzo",
        "aprile",
        "maggio",
        "giugno",
        "luglio",
        "agosto",
        "settembre",
        "ottobre",
        "novembre",
        "dicembre"
    ];
    var month = months[monthIndex];
    var formattedDate = day + " " + month + " " + year;
    return formattedDate;
}