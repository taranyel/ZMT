<?php
function isUserValid(string $name, string $surname, string $email, string $username): array
{
    $error[0] = isNameValid($name);
    $error[1] = isNameValid($surname);
    $error[2] = isEmailValid($email);
    $error[3] = isUsernameValid($username);

    return $error;
}

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

function isPasswordValid(string $password): string
{
    $passwordErr = "";
    if (strlen($password) < 8) {
        $passwordErr = "At least 8 characters are required!";
    } else if (strlen($password) > 100) {
        $passwordErr = "Maximum 100 characters are allowed!";
    } else if (!preg_match("#[0-9]+#", $password)) {
        $passwordErr = "At least 1 number are required!";
    } else if (!preg_match("#[A-Z]+#", $password)) {
        $passwordErr = "At least 1 capital letter are required!";
    } else if (!preg_match("#[a-z]+#", $password)) {
        $passwordErr = "At least 1 lowercase letter are required!";
    }
    return $passwordErr;
}

function isMessageValid(string $title, string $message): array
{
    $error[0] = isTitleValid($title);
    $error[1] = isContentValid($message);

    return $error;
}

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