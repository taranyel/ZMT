<?php
include_once "../../assets/connection.php";
include_once "../../assets/message_functions.php";
include_once "../../assets/page_functions.php";

connection();
session_start();

$id_message = $_GET["id_message"];
validateDigitGetParam(strval($id_message));

$data = mysqli_fetch_array(getMessageDetails($id_message));
$id_user = $data["id_user"];

deleteMessage($id_message);

header("location: ../../profile/profile.php?id_user=$id_user");
