<?php
include "../../assets/connection.php";
include "../../assets/user_functions.php";

connection();
session_start();

$tmp_email = "";

$error = ["", ""];

if (isset($_POST["login"])) {
    $error = login();

    if ($error != []) {
        $tmp_email = htmlspecialchars($_POST["email"]);
    } else {
        header("location: ../../profile/profile.php?id_user=$_SESSION[id_user]");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Log in</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../form_style/form.css">
    <link rel="stylesheet" href="../form_style/login.css">
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
        </div>
    </header>

    <div class="form_content">
        <div class="screen_login">

            <div class="options">
                <a href='sign_up.php' class="option_link">Sign up</a>
                <a href='login.php' class='option_link' id="visited_option">Log in</a>
            </div>

            <div class="screen__content">
                <form class="form" action="login.php" method="post">
                    <div class="input__field">
                        <label>*
                            <input type="email" name="email" placeholder="Email"
                                   value="<?php echo $tmp_email?>" minlength="4" maxlength="50" required>
                        </label>
                        <div class="response"><?php echo $error[0]?></div>
                    </div>

                    <div class="input__field">
                        <label>*
                            <input type="password" name="password" placeholder="Password" minlength="8" maxlength="100" required>
                        </label>
                        <div class="response"><?php echo $error[1]?></div>
                    </div>
                    <button class="form__submit" type="submit" name="login">
                        <span class="button__text">Log in</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>