<?php
include_once "../../assets/connection.php";
include_once "../../assets/message_functions.php";
include_once "../../assets/page_functions.php";

connection();
session_start();

if (!isset($_GET["id_message"]) || empty($_GET["id_message"])) {
    header("location: ../../index.php?id_page=5&offset=0");
}

$id_message = validateIdUserMessageParam(strval($_GET["id_message"]));

$data = mysqli_fetch_array(getMessageDetails($id_message));
if (!$data) {
    header("location: ../../index.php?id_page=5&offset=0");
}

$id_user = $data["id_user"];

if (isset($_SESSION["id_user"]) && ($id_user == $_SESSION["id_user"] || isset($_SESSION["admin"]))) {
    deleteMessage($id_message);
    header("location: ../../profile/profile.php?id_user=$id_user");
} else {
    header("location: ../../index.php?id_page=5&offset=0");
}

