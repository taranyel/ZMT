<?php
include "../../assets/connection.php";
include "../../assets/user_functions.php";

connection();
echo giveResponse();

/**
 * @return string
 */
function giveResponse(): string
{
    $result = "invalid";
    if (!empty($_GET["username"])) {
        $data = $_GET["username"];

        if (isUsernameAvailable($data)) {
            $result = "valid";
        }

    } elseif (!empty($_GET["email"])) {
        $data = $_GET["email"];

        if (isEmailAvailable($data)) {
            $result = "valid";
        }
    }
    return $result;
}




