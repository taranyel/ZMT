<?php
include_once "../../assets/connection.php";
include_once "../../assets/user_functions.php";
include_once "../../assets/page_functions.php";

connection();
session_start();

$id_user = "";
$error = "";
$tmp_email = "";

if (!isset($_SESSION["id_user"])) {
    header("location: ../../index.php?id_page=5&offset=0");
} else {
    $id_user = $_SESSION["id_user"];
    $tmp_email = mysqli_fetch_array(getUserDetails($id_user))["email"];

    if (isset($_GET["save"])) {
        $tmp_email = htmlspecialchars($_GET["email"]);
        $error = changeEmail($id_user);

        if ($error == ""){
            header("location: ../../profile/profile.php?id_user=$id_user");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change email</title>
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
        </div>
    </header>

    <div class="form_content">
        <div class="screen_email">

            <div class="options">
                <span class="option_link">Change email</span>
            </div>

            <div class="screen__content">
                <form class="form" action="change_email.php" method="get">
                    <div class="input__field">
                        <label for="email">*</label>
                        <input type="email" class="input" id="email" name="email" placeholder="New email" value="<?php echo $tmp_email?>" minlength="4" maxlength="70" required>
                        <div class="response" id="email_response"><?php echo $error?></div>
                    </div>

                    <button class="form__submit" id="form__submit" type="submit" name="save">
                        <span class="button__text">Save</span>
                    </button>
                </form>
                <script src="../client_validation/check_email.js"></script>
            </div>
        </div>
    </div>
</div>
</body>
</html>