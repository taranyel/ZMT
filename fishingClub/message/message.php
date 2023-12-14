<?php
include_once "../assets/connection.php";
include_once "../assets/message_functions.php";
include_once "../assets/page_functions.php";

connection();
session_start();

$id_message = $_GET["id_message"];
$id_user = $_GET["id_user"];

validateDigitGetParam(strval($id_message));
validateDigitGetParam(strval($id_user));

$message = mysqli_fetch_array(getMessageDetails($id_message));

if ($message) {
    $title = htmlspecialchars($message["name"]);
    $content = $message["content"];
    $image = $message["image"];
    $date = $message["date"];
    $id_page = $message["id_page"];

    $page = mysqli_fetch_array(getPageName($id_page))["name"];
    $user = mysqli_fetch_array(getUserDetails($id_user));
    $username = $user["username"];
} else {
    $title = "";
    $content = "";
    $image = "";
    $date = "";
    $username = "";
    $page = "";
    $id_page = "";
    header("location: ../profile/profile.php?id_user=$id_user");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $title ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../page.css">
    <link rel="stylesheet" href="message.css">
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
                echo "<a href='../profile/profile.php?id_user=$_SESSION[id_user]' class='header_link' id='profile_link'>My profile</a>";
            }
            if (empty($_SESSION["id_user"])) {
                echo "<a href='../forms/form/login.php' class='header_link' id='sign_link'>Log in </a>";
                echo "<a href='../forms/form/sign_up.php?action=sign_up' class='header_link' id='sign_link'>Sign up</a>";
            }
            ?>
            <a href='../about_us/about_us.php' class='about_link'>About us</a>
            <a href='../about_us/contacts.php' class='about_link'>Contacts</a>
        </div>
    </header>

    <div class="content">
        <div class="navigation">
            <a href="../index.php?id_page=5&offset=0" class='nav_link'>Main page</a>
            <a href="../index.php?id_page=1&offset=0" class='nav_link'>Fishing rules</a>
            <a href="../index.php?id_page=2&offset=0" class='nav_link'>Locations</a>
            <a href="../index.php?id_page=3&offset=0" class='nav_link'>Stories</a>
            <a href="../index.php?id_page=4&offset=0" class='nav_link'>Photo gallery</a>
        </div>

        <div class="message_block">
            <div class="message_info">
                <div class="info">
                    <label class="message_label">
                        <span class="message_label_text"><?php echo $title ?></span>
                    </label>
                    <div class="user_info">
                        <a href="../index.php?id_page=<?php echo $id_page?>&offset=0" class="mess_header_link"><?php echo $page?></a>
                        <a href='../profile/profile.php?id_user=<?php echo $id_user ?>' class="mess_header_link"> <?php echo htmlspecialchars($username) ?></a>
                        <p class="mess_header_link"><?php echo $date ?></p>
                    </div>
                </div>

                <?php
                if ($image != null) {
                    echo "<img src='../message/message_images/$image' height='150px' width='300px' alt='$title' class='message_image'>";
                }
                ?>

                <div class="message_content_prev">
                    <?php showByParagraphs($content); ?>
                </div>

                <div class="message_link">
                    <?php
                    if (!empty($_SESSION["admin"]) || (!empty($_SESSION["id_user"]) && $id_user == $_SESSION["id_user"])) {
                        echo "<a href='../forms/form/add_update_message.php?id_message=$id_message&id_user=$id_user' class='message_button'><span>Edit message</span></a>";
                    }
                    if (!empty($_SESSION["admin"]) || ((!empty($_SESSION["id_user"]) && $id_user == $_SESSION["id_user"]))) {
                        echo "<a href='../forms/delete/delete_message.php?id_message=$id_message' class='message_button'><span>Delete message</span></a>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <footer></footer>
</div>
</body>
</html>
