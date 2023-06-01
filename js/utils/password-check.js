export { };

/**
 * Function used to check if passwords are equal.
 * @param password
 * @param passwordToCheck
 * @returns true if the password is valid, false otherwise.
 */
export function passwordEqual(password, passwordToCheck) {
    let valid = password === passwordToCheck;
    return valid;
}

export function passwordValid(password) {
    return password.length >= 8 && !/^[0-9a-zA-Z]+$/.test(password);
}