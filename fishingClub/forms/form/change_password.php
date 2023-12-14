<?php
include_once "../../assets/connection.php";
include_once "../../assets/user_functions.php";
include_once "../../assets/page_functions.php";

connection();
session_start();

$error = ["", ""];

$id_user = $_SESSION["id_user"];
validateDigitGetParam(strval($id_user));

if (isset($_POST["save"])) {
    $error = changePassword($id_user);

    if ($error == []){
        header("location: ../../profile/profile.php?id_user=$id_user");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../page.css">
    <link rel="stylesheet" href="../form_style/form.css">
    <link rel="stylesheet" href="../form_style/change_password.css">
</head>
<body>
<div class="container">
    <header>
        <div class="logo">
            <a href="../../index.php?id_page=5&offset=0"><img src="../../assets/logo.jpg" alt="logo" class="logo_image"></a>
            <a href="../../index.php?id_page=5&offset=0" class="logo_label">angler</a>
        </div>

        <div class="header_links_block">
            <?php
            echo "<a href='../../profile/profile.php?id_user=$id_user' class='header_link' id='profile_link'>My profile</a>";
            ?>
            <a href='../../about_us/about_us.php' class='about_link'>About us</a>
            <a href='../../about_us/contacts.php' class='about_link'>Contacts</a>
        </div>
    </header>

    <div class="form_content">
        <div class="screen_password">

            <div class="options">
                <span class="option_link">Change password</span>
            </div>

            <div class="screen__content">
                <form class="form" action="change_password.php" method="post">
                    <div class="input__field">
                        <label for="password">*</label>
                        <input type="password" class="input" id="password" name="password" placeholder="New password" minlength="8" maxlength="100" required>
                        <div class="response" id="password_response"><?php echo $error[0]?></div>
                    </div>

                    <div class="input__field">
                        <label for="confirm_password">*</label>
                        <input type="password" class="input" id="confirm_password" name="confirm_password" placeholder="Repeat your password" minlength="8" maxlength="100" required>
                        <script src="../client_validation/compare_passwords.js"></script>
                        <div class="response" id="conf_password_response"><?php echo $error[1]?></div>
                    </div>

                    <button class="form__submit" type="submit" name="save">
                        <span class="button__text">Save</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>