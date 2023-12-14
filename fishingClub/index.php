<?php
include_once "assets/connection.php";
include_once "assets/message_functions.php";
include_once "assets/page_functions.php";

connection();
session_start();

if (!isset($_GET["id_page"]) && !isset($_GET["offset"])) {
    $_GET["id_page"] = 5;
    $_GET["offset"] = 0;
}

$id_page = $_GET["id_page"];
$current_offset = $_GET["offset"];

validateDigitGetParam(strval($id_page));
validateDigitGetParam(strval($current_offset));

$offset = 2 * $current_offset;


$data = getPageName($id_page);
$name = mysqli_fetch_array($data)["name"];

if ($current_offset == 0) {
    $_SESSION["amount_of_all_messages"] = getAmountOfAllMessagesByPage($id_page);
    $_SESSION["amount_of_all_pages"] = ceil($_SESSION["amount_of_all_messages"] / 2);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo "$name" ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="page.css">
</head>

<body>
<div class="screen">
    <header>
        <div class="logo">
            <a href="index.php?id_page=5&offset=0"><img src="assets/logo.jpg" alt="logo" class="logo_image"></a>
            <a href="index.php?id_page=5&offset=0" class="logo_label">angler</a>
        </div>

        <div class="header_links_block">
            <?php
            if (!empty($_SESSION["id_user"])) {
                echo "<a href='profile/profile.php?id_user=$_SESSION[id_user]' class='header_link' id='profile_link'>My profile</a>";
            } else {
                echo "<a href='forms/form/login.php' class='header_link'>Log in</a>";
                echo "<a href='forms/form/sign_up.php' class='header_link'>Sign up</a>";
            }
            ?>
            <a href='about_us/about_us.php' class='about_link'>About us</a>
            <a href='about_us/contacts.php' class='about_link'>Contacts</a>
        </div>
    </header>

    <div class="content">

        <div class="navigation">
            <a href="index.php?id_page=5&offset=0" id="<?php if ($id_page == 5) {
                echo "visited";
            } ?>" class='nav_link'>Main page</a>
            <a href="index.php?id_page=1&offset=0" id="<?php if ($id_page == 1) {
                echo "visited";
            } ?>" class='nav_link'>Fishing rules</a>
            <a href="index.php?id_page=2&offset=0" id="<?php if ($id_page == 2) {
                echo "visited";
            } ?>" class='nav_link'>Locations</a>
            <a href="index.php?id_page=3&offset=0" id="<?php if ($id_page == 3) {
                echo "visited";
            } ?>" class='nav_link'>Stories</a>
            <a href="index.php?id_page=4&offset=0" id="<?php if ($id_page == 4) {
                echo "visited";
            } ?>" class='nav_link'>Photo gallery</a>
        </div>

        <div class="message_block">
            <div class="content_block">
                <?php getMessageByPage($id_page, $offset); ?>
            </div>

            <div class="pagination">
                <?php
                if ($current_offset != 0) {
                    $offset_to_pass = $current_offset - 1;
                    echo "<a href='index.php?id_page=$id_page&offset=$offset_to_pass' class='pagination_link'><span class='previous'><- Previous</span></a>";
                } else {
                    echo "<div class='empty_link'></div>";
                }

                showPagination("index.php?id_page", $id_page, $current_offset + 1);

                if ($offset + 2 < $_SESSION["amount_of_all_messages"]) {
                    $offset_to_pass = $current_offset + 1;
                    echo "<a href='index.php?id_page=$id_page&offset=$offset_to_pass' class='pagination_link'><span class='next'>Next -></span></a>";
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