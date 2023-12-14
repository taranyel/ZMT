<?php
include "../../assets/connection.php";
include "../../assets/user_functions.php";

connection();

$result = file_get_contents("php://input");
if (!empty($_GET["username"])){
    $data = $_GET["username"];

    if (!isUsernameAvailable($data)) {
        echo "invalid";
    } else {
        echo "valid";
    }

} elseif (!empty($_GET["email"])){
    $data = $_GET["email"];

    if (!isEmailAvailable($data)) {
        echo "invalid";
    } else {
        echo "valid";
    }
}



