<?php
include_once "connection.php";
include_once "page_functions.php";

connection();
session_start();
changeRole();

/**
 * <p>The <b>changeRole()</b> function updates user's role in the database.
 * It also validates given GET parameters and in case of validation failure, redirects user to the main page.</p>
 * @return void
 */
function changeRole(): void
{
    global $connection;

    if (!isset($_GET["id_user"]) || empty($_GET["id_user"]) || !isset($_GET["role"]) || empty($_GET["role"])) {
        header("location: ../index.php?id_page=5&offset=0");
    }

    $role = $_GET["role"];

    $id_user = validateIdUserMessageParam(strval($_GET["id_user"]));

    if (isset($_SESSION["admin"])) {
        if ($role == "user"){
            $query = "UPDATE user SET role = 'admin' WHERE id_user = '$id_user'";

            mysqli_query($connection, $query);
            header("location: ../profile/profile.php?id_user=$id_user");

        } elseif ($role == "admin") {
            $query = "UPDATE user SET role = 'user' WHERE id_user = '$id_user'";

            mysqli_query($connection, $query);
            header("location: ../profile/profile.php?id_user=$id_user");
        }
    } else {
        header("location: ../index.php?id_page=5&offset=0");
    }
}
