<?php
include_once "../../assets/user_functions.php";
include_once "../../assets/connection.php";
include_once "../../assets/page_functions.php";

connection();
session_start();

$id_user = $_GET["id_user"];
validateDigitGetParam(strval($id_user));

deleteUser($id_user);

session_unset();
session_destroy();
header("location: ../../index.php?id_page=5&offset=0");