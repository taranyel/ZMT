<?php
include_once "../assets/connection.php";
include_once "../assets/message_functions.php";
include_once "../assets/page_functions.php";

connection();
session_start();

if (!isset($_GET["offset"])) {
    $_GET["offset"] = 0;
}

if (!isset($_GET["id_user"]) || empty($_GET["id_user"])) {
    if (isset($_SESSION["id_user"])){
        $_GET["id_user"] = $_SESSION["id_user"];
    } else {
        header("location: ../index.php?id_page=5&offset=0");
    }
}

$current_offset = validateOffsetParam(strval($_GET["offset"]));
$id_user = validateIdUserMessageParam(strval($_GET["id_user"]));

$offset = 2 * $current_offset;

$_SESSION["amount_of_all_messages"] = getAmountOfAllMessagesByUser($id_user);
$_SESSION["amount_of_all_pages"] = ceil($_SESSION["amount_of_all_messages"] / 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php
        if (!empty($_SESSION["id_user"]) && $_SESSION["id_user"] == $id_user) {
            echo "My articles";
        } else {
            echo "User articles";
        } ?>
    </title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../page.css">
</head>

<body>
<div class="screen">
    <header>
        <div class="logo">
            <a href="../index.php?id_page=5&offset=0"><img src="../assets/logo.jpg" alt="logo" class="logo_image"></a>
            <a href="../index.php?id_page=5&offset=0" class="logo_label">angler</a>
        </div>

        <div class="header_links_block">
            <?php
            if (!empty($_SESSION["id_user"])) {
                echo "<a href='profile.php?id_user=$_SESSION[id_user]' class='header_link' id='profile_link'>My profile</a>";
            } else {
                echo "<a href='../forms/form/login.php' class='header_link'>Log in</a>";
                echo "<a href='../forms/form/sign_up.php' class='header_link'>Sign up</a>";
            }
            ?>
            <a href='../about_us/about_us.php' class='about_link'>About us</a>
        </div>
    </header>

    <div class="content">
        <div class="navigation">
            <a href="user_messages.php?id_user=<?php echo $id_user ?>&offset=0" class="nav_link"><?php
                if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                    echo "My articles";
                } else {
                    echo "User articles";
                } ?></a>
        </div>
        <div class="message_block">
            <div class="content_block">
                <?php $messages_amount = getMessageByUser($id_user, $offset); ?>
            </div>

            <div class="pagination">
                <?php
                if ($messages_amount != 0 && $current_offset != 0) {
                    $offset_to_pass = $current_offset - 1;
                    echo "<a href='user_messages.php?id_user=$id_user&offset=$offset_to_pass' class='pagination_link'><span class='previous'><- Previous</span></a>";
                } else {
                    echo "<div class='empty_link'></div>";
                }

                if ($messages_amount != 0){
                    showPagination("user_messages.php?id_user", $id_user, $current_offset + 1);
                }

                if ($offset + 2 < $_SESSION["amount_of_all_messages"]) {
                    $offset_to_pass = $current_offset + 1;
                    echo "<a href='user_messages.php?id_user=$id_user&offset=$offset_to_pass' class='pagination_link'><span class='next'>Next -></span></a>";
                } else {
                    echo "<div class='empty_link'></div>";
                }
                ?>
            </div>
        </div>
    </div>
    <footer></footer>
</div>
</body>
</html>