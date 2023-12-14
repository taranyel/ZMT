<?php
include_once "connection.php";
include_once "page_functions.php";

connection();
session_start();

global $connection;

$id_user = $_GET["id_user"];
$role = $_GET["role"];

validateDigitGetParam(strval($id_user));

if ($role == "user"){
    $query = "UPDATE user SET role = 'admin' WHERE id_user = '$id_user'";

    mysqli_query($connection, $query);
    header("location: ../profile/profile.php?id_user=$id_user");

} elseif ($role == "admin") {
    $query = "UPDATE user SET role = 'user' WHERE id_user = '$id_user'";

    mysqli_query($connection, $query);
    header("location: ../profile/profile.php?id_user=$id_user");
} else {
    header("HTTP/1.0 404 Not Found");
    header("location: ../error_page.php");
}