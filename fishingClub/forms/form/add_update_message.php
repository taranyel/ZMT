<?php
include_once "../../assets/connection.php";
include_once "../../assets/message_functions.php";
include_once "../../assets/page_functions.php";

connection();
session_start();

$error = ["", "", ""];

if (isset($_GET["id_message"])) {
    $action = "Edit message";

    $id_user = $_GET["id_user"];
    $id_message = $_GET["id_message"];

    validateDigitGetParam(strval($id_user));
    validateDigitGetParam(strval($id_message));

    $message = mysqli_fetch_array(getMessageDetails($id_message));
    $tmp_title = $message["name"];
    $tmp_content = $message["content"];

    if (isset($_POST["submit"])) {
        $error = updateMessage($id_message);

        $tmp_title = htmlspecialchars($_POST["title"]);
        $tmp_content = htmlspecialchars($_POST["message"]);

        if ($error == []) {
            header("location: ../../profile/profile.php?id_user=$id_user");
        }
    }

} else {
    $action = "Add message";

    $tmp_title = "";
    $tmp_content = "";

    if (isset($_POST["submit"])) {
        $error = addMessage();

        $tmp_title = htmlspecialchars($_POST["title"]);
        $tmp_content = htmlspecialchars($_POST["message"]);

        if ($error == []) {
            header("location: ../../profile/profile.php?id_user=$_SESSION[id_user]");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $action?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../page.css">
    <link rel="stylesheet" href="../form_style/add_update_message.css">
</head>
<body>
<div class="screen">
    <header>
        <div class="logo">
            <a href="../../index.php?id_page=5&offset=0"><img src="../../assets/logo.jpg" alt="logo" class="logo_image"></a>
            <a href="../../index.php?id_page=5&offset=0" class="logo_label">angler</a>
        </div>

        <div class="header_links_block">
            <?php
            if (!empty($_SESSION["id_user"])) {
                echo "<a href='../../profile/profile.php?id_user=$_SESSION[id_user]' class='header_link' id='profile_link'>My profile</a>";
            }
            ?>
            <a href='../../about_us/about_us.php' class='about_link'>About us</a>
            <a href='../../about_us/contacts.php' class='about_link'>Contacts</a>
        </div>
    </header>

    <div class="content">
        <div class="navigation">
            <a href="../../index.php?id_page=5&offset=0" class='nav_link'>Main page</a>
            <a href="../../index.php?id_page=1&offset=0" class='nav_link'>Fishing rules</a>
            <a href="../../index.php?id_page=2&offset=0" class='nav_link'>Locations</a>
            <a href="../../index.php?id_page=3&offset=0" class='nav_link'>Stories</a>
            <a href="../../index.php?id_page=4&offset=0" class='nav_link'>Photo gallery</a>
        </div>

        <div class="message_block">
            <div class="add_message">
                <form action="add_update_message.php<?php if (!empty($_GET)) {
                    echo "?id_message=$_GET[id_message]&id_user=$_GET[id_user]";
                } ?>" method="post" enctype="multipart/form-data">
                    <?php
                    if (empty($_GET["id_message"])) {
                        echo "<div class='add_field'>
                    <label>Select category:
                        <select class='select' name='page'>";
                        fillPageName();
                        echo "</select>
                    </label>
                        </div>";
                    }
                    ?>

                    <div class='add_field'>
                        <label>Title: <span class="required">*</span>
                            <input type="text" name="title"  class="add_title" value="<?php echo $tmp_title ?>" >
                        </label>
                        <div class="message_response"><?php echo $error[0] ?></div>
                    </div>

                    <div class='add_field'>
                        <label >Text: <span class="required">*</span>
                            <textarea name="message" rows="10" cols="20" class="add_content"  ><?php echo $tmp_content ?></textarea>
                        </label>
                        <div class="message_response"><?php echo $error[1] ?></div>
                    </div>

                    <?php if (empty($_GET["id_message"])) {
                        echo "<div class='add_field'>
                                <label class='image_label'>Image:
                                    <input type='file' name='image' class='add_image' accept='image/*'>
                                </label>
                                <div class='message_response'>$error[2]</div>
                               </div>";
                    }
                    ?>
                    <input class="submit_message" type="submit" name="submit" value="Save">
                </form>
            </div>

        </div>
    </div>
    <footer></footer>
</div>
</body>
</html>