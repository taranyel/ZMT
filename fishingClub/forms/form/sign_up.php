<?php
include "../../assets/connection.php";
include "../../assets/user_functions.php";

connection();
session_start();

$error = ["", "", "", "", "", ""];

$tmp_name = "";
$tmp_surname = "";
$tmp_email = "";
$tmp_username = "";

if (isset($_POST["submit"])) {
    $error = addUser();

    $tmp_name = htmlspecialchars($_POST["name"]);
    $tmp_surname = htmlspecialchars($_POST["surname"]);
    $tmp_email = htmlspecialchars($_POST["email"]);
    $tmp_username = htmlspecialchars($_POST["username"]);

    if ($error == []) {
        header("location: login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign up</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../form_style/form.css">
    <link rel="stylesheet" href="../form_style/sign_up.css">
    <link rel="stylesheet" href="../../page.css">
</head>
<body>
<div class="container">
    <header>
        <div class="logo">
            <a href="../../index.php?id_page=5&offset=0"><img src="../../assets/logo.jpg" alt="logo" class="logo_image"></a>
            <a href="../../index.php?id_page=5&offset=0" class="logo_label">angler</a>
        </div>

        <div class="header_links_block">
            <a href='../../about_us/about_us.php' class='about_link'>About us</a>
            <a href='../../about_us/contacts.php' class='about_link'>Contacts</a>
        </div>
    </header>

    <div class="form_content">
        <div class="screen_sign_up">
            <div class="options">
                <a href='sign_up.php' class="option_link" id="visited_option">Sign up</a>
                <a href='login.php' class='option_link'>Log in</a>
            </div>

            <div class="screen__content">
                <form class="form" action="sign_up.php" method="post">
                    <div class="user_info_block">
                        <div class="data">
                            <div class="input__field">
                                <label>*
                                    <input type="text" name="name" placeholder="Name"
                                           value="<?php echo $tmp_name ?>">
                                </label>
                                <div class="response"><?php echo $error[0] ?></div>
                            </div>

                            <div class="input__field">
                                <label>*
                                    <input type="text" name="surname" placeholder="Surname"
                                           value="<?php echo $tmp_surname ?>">
                                </label>
                                <div class="response"><?php echo $error[1] ?></div>
                            </div>
                        </div>

                        <div class="data">
                            <div class=input__field>
                                <label for="email">*</label>
                                <input type="email" id="email" name="email" placeholder="Email"
                                       value="<?php echo $tmp_email ?>">

                                <div class="response" id="email_response"><?php echo $error[2] ?></div>
                            </div>

                            <div class=input__field>
                                <label for="username">*</label>
                                <input type="text" id="username" name="username" placeholder="Username"
                                       value="<?php echo $tmp_username ?>">

                                <div class="response" id="username_response"><?php echo $error[3] ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="password_info">
                        <div class="input__field">
                            <label for="password">*</label>
                            <input type="password" id="password" name="password" placeholder="New password">
                            <div class="response"><?php echo $error[4] ?></div>
                        </div>

                        <div class="input__field">
                            <label for="confirm_password">*</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                   placeholder="Repeat your password">
                            <div class="response" id="conf_password_response"><?php echo $error[5] ?></div>
                        </div>
                    </div>

                    <button class="form__submit" type="submit" name="submit">
                        <span class="button__text">Sign up</span>
                    </button>

                    <script src="../client_validation/check_email.js"></script>
                    <script src="../client_validation/check_username.js"></script>
                    <script src='../client_validation/compare_passwords.js'></script>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

