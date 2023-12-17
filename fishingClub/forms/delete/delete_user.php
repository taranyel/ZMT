<?php
include_once "../../assets/user_functions.php";
include_once "../../assets/connection.php";
include_once "../../assets/page_functions.php";

connection();
session_start();

if (!isset($_GET["id_user"]) || empty($_GET["id_user"])) {
    header("location: ../../index.php?id_page=5&offset=0");
}

$id_user = validateIdUserMessageParam(strval($_GET["id_user"]));

$user = mysqli_fetch_array(getUserDetails($id_user));
if (!$user) {
    header("location: ../../index.php?id_page=5&offset=0");
}

if (isset($_SESSION["id_user"]) && ($id_user == $_SESSION["id_user"] || isset($_SESSION["admin"]))) {
    deleteUser($id_user);
    session_unset();
    session_destroy();
}
header("location: ../../index.php?id_page=5&offset=0");

