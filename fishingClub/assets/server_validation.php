<?php

/**
 * <p>The <b>isUserValid()</b> function collects validation results from another functions, which
 * validate user data.</p>
 * @param string $name <p>is given user name.</p>
 * @param string $surname <p>is given user surname.</p>
 * @param string $email <p>is given user email.</p>
 * @param string $username <p>is given user username.</p>
 * @return array <p>
 * Function must return an array of errors, which were discovered during validation.</p>
 */
function isUserValid(string $name, string $surname, string $email, string $username): array
{
    $error[0] = isNameValid($name);
    $error[1] = isNameValid($surname);
    $error[2] = isEmailValid($email);
    $error[3] = isUsernameValid($username);

    return $error;
}

/**
 * <p>The <b>isEmailValid()</b> function validates given email.</p>
 * @param string $email <p>is given email.</p>
 * @return string <p>
 *  Function must return detected email error.</p>
 */
function isEmailValid(string $email): string
{
    $emailErr = "";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format!";
    } else if (strlen($email) < 4) {
        $emailErr = "At least 3 characters are required!";
    }
    return $emailErr;
}

/**
 * <p>The <b>isNameValid()</b> function validates given name.</p>
 * @param string $name <p>is given name.</p>
 * @return string <p>
 *   Function must return detected name error.</p>
 */
function isNameValid(string $name): string
{
    $nameErr = "";
    if ((strlen($name) < 2) || !preg_match("#[a-zA-Z]+#", $name)) {
        $nameErr = "At least 2 characters are required!";
    } else if (strlen($name) > 40) {
        $nameErr = "Maximum 40 characters are allowed!";
    } else if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameErr = "Only letters and white space are allowed!";
    }
    return $nameErr;
}

/**
 * <p>The <b>isUsernameValid()</b> function validates given username.</p>
 * @param string $username <p>is given name.</p>
 * @return string <p>
 *    Function must return detected username error.</p>
 */
function isUsernameValid(string $username): string
{
    $usernameErr = "";
    if (strlen($username) < 4) {
        $usernameErr = "At least 4 characters are required!";
    } else if (strlen($username) > 100) {
        $usernameErr = "Maximum 100 characters are allowed!";
    } else if (!preg_match("/^[a-zA-Z0-9._-]+$/", $username)) {
        $usernameErr = "Only letters, digits, -, _, . are allowed!";
    }
    return $usernameErr;
}

/**
 * <p>The <b>isPasswordValid()</b> function validates given password.</p>
 * @param string $password <p>is given password.</p>
 * @return string <p>
 *     Function must return detected password error.</p>
 */
function isPasswordValid(string $password): string
{
    $passwordErr = "";
    if (strlen($password) < 8) {
        $passwordErr = "At least 8 characters are required!";
    } else if (strlen($password) > 100) {
        $passwordErr = "Maximum 100 characters are allowed!";
    } else if (!preg_match("#[0-9]+#", $password)) {
        $passwordErr = "At least 1 number is required!";
    } else if (!preg_match("#[A-Z]+#", $password)) {
        $passwordErr = "At least 1 capital letter is required!";
    } else if (!preg_match("#[a-z]+#", $password)) {
        $passwordErr = "At least 1 lowercase letter is required!";
    }
    return $passwordErr;
}

/**
 * <p>The <b>isMessageValid()</b> function collects validation results from another functions, which
 *  validate article data.</p>
 * @param string $title <p>is given article title.</p>
 * @param string $message <p>is given article text.</p>
 * @return array <p>
 *  Function must return an array of errors, which were discovered during validation.</p>
 */
function isMessageValid(string $title, string $message): array
{
    $error[0] = isTitleValid($title);
    $error[1] = isContentValid($message);

    return $error;
}

/**
 * <p>The <b>isTitleValid()</b> function validates given article title.</p>
 * @param string $title <p>is given title.</p>
 * @return string <p>
 *      Function must return detected article title error.</p>
 */
function isTitleValid(string $title): string
{
    $titleErr = "";
    if (strlen($title) < 2) {
        $titleErr = "At least 2 characters are required!";
    } else if (strlen($title) > 30) {
        $titleErr = "Maximum 30 characters are allowed!";
    } else if (!preg_match("#[a-zA-Z0-9^\w]+#", $title)) {
        $titleErr = "Only letters, digits, -, _, . are allowed!";
    }
    return $titleErr;
}

/**
 * <p>The <b>isTitleValid()</b> function validates given article text.</p>
 * @param string $message <p>is given article text.</p>
 * @return string <p>
 *       Function must return detected article text error.</p>
 */
function isContentValid(string $message): string
{
    $messageErr = "";
    if (strlen($message) < 2) {
        $messageErr = "At least 2 characters required!";
    } else if (strlen($message) > 65534) {
        $messageErr = "Maximum 65535 characters allowed!";
    } else if (!preg_match("#[a-zA-Z0-9^\w]+#", $message)) {
        $messageErr = "Only letters, digits, -, _, . are allowed!";
    }
    return $messageErr;
}