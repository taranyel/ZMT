<?php
include_once "../assets/message_functions.php";
include_once "../assets/connection.php";
include_once "../assets/page_functions.php";

connection();
session_start();

$id_user = $_GET["id_user"];
validateDigitGetParam(strval($id_user));

$error[0] = "";
$error[1] = "";
$error[2] = "";
$error[3] = "";

$data = mysqli_fetch_array(getUserDetails($id_user));
if ($data) {
    $name = $data["name"];
    $surname = $data["surname"];
    $email = $data["email"];
    $username = $data["username"];
    $role = $data["role"];
} else {
    $name = "";
    $surname = "";
    $role = "";
    $email = "";
    $username = "";
}

if (isset($_POST["save"])) {
    $error = updateUser($id_user);

    $name = htmlspecialchars($_POST["name"]);
    $surname = htmlspecialchars($_POST["surname"]);
    $email = htmlspecialchars($_POST["email"]);
    $username = htmlspecialchars($_POST["username"]);

    if ($error == []) {
        header("location: profile.php?id_user=$id_user");
    } else {
        echo "<script src='edit_after_error.js' defer></script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo "$data[name] $data[surname]" ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../page.css">
    <link rel="stylesheet" href="profile.css">
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
            if (!empty($_SESSION["id_user"]) && $_SESSION["id_user"] != $id_user) {
                echo "<a href='profile.php?id_user=$_SESSION[id_user]' class='header_link' id='profile_link'>My profile</a>";
            }
            if (empty($_SESSION["id_user"])) {
                echo "<a href='../forms/form/login.php' class='header_link' id='sign_link'>Log in </a>";
                echo "<a href='../forms/form/sign_up.php?action=sign_up' class='header_link'>Sign up</a>";
            }
            if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                echo "<a href='../forms/form/logout.php' class='header_link'>Log out</a>";
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
        <div class="info_block">
            <div class="role_block">
                <label class="main_label" id="info_label">
                    <span class="main_label_text">Personal information:</span>
                </label>

                <div class="role">
                    <label class="container">Administrator
                        <input type="radio" name="role" <?php if ($role == "admin") {
                            echo "checked";
                        } ?>
                               disabled>
                        <span class="checkmark"></span>
                    </label>

                    <label class="container">User
                        <input type="radio" name="role" value="user" <?php if ($role != "admin") {
                            echo "checked";
                        } ?>
                               disabled>
                        <span class="checkmark"></span>
                    </label>
                    <?php
                    if (!empty($_SESSION["admin"])) {
                        echo "<a href='../assets/change_role.php?id_user=$id_user&role=$role'
                        class='profile_button' id='change_role'>Change role</a>";
                    }
                    if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                        echo " <button type='button' id='change_info'>i</button>";
                    }
                    ?>
                </div>

            </div>
            <div class="personal_info_block">
                <div class="user_block">
                    <form action='profile.php?id_user=<?php echo $id_user ?>' method='post'>
                        <label>Name:
                            <input type='text' class='info_text' name='name' id='edit_name'
                                   value='<?php echo $name ?>'
                                   minlength="2" maxlength="100" readonly required>
                        </label>
                        <div class="response"><?php echo $error[0] ?></div>

                        <label>Surname:
                            <input type='text' class='info_text' name='surname' id='edit_surname'
                                   value='<?php echo $surname ?>' minlength="2" maxlength="100" readonly required>
                        </label>
                        <div class="response"><?php echo $error[1] ?></div>

                        <label id="email">Email:
                            <input type='text' class='info_text' name='email' id='edit_email'
                                   value='<?php echo $email ?>'
                                   minlength="4" maxlength="100" readonly required>
                        </label>
                        <div class="response"><?php echo $error[2] ?></div>


                        <label>Username:
                            <input type='text' class='info_text' name='username' id='edit_username'
                                   value='<?php echo $username ?>' minlength="4" maxlength="100" readonly required>
                        </label>
                        <div class="response"><?php echo $error[3] ?></div>

                        <label id='edit_submit'></label>
                    </form>

                    <?php if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                        echo "
                        <div class='personal_link'>
                            <button class='profile_button' id='edit_profile'>Edit my profile</button>
                            <script src='edit_profile.js'></script>
                            
                            <a href='../forms/form/change_password.php' class='profile_button'>Change password</a>
                            <a href='../forms/form/add_update_message.php' class='profile_button' id='add_message_button'>Add article</a>
                        </div>";
                    }
                    ?>
                </div>
                <?php
                if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                    echo "<div id='change_role_text'></div>";
                    echo "<script src='change_role_info.js'></script>";
                }
                ?>
            </div>

            <div class="articles_link">
                <a id="go_to_messages"
                   href="user_messages.php?id_user=<?php echo $id_user ?>&offset=0"><span class="profile_button" >
                        <?php
                        if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                            echo "Go to my articles";
                        } else {
                            echo "Go to user articles";
                        } ?>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <footer>
        <?php
        if (!empty($_SESSION["id_user"]) && (($_SESSION["id_user"] == $id_user) || !empty($_SESSION["admin"]))) {
            echo "<div>
                    <button class='profile_button' id='delete_button'>Delete account</button>
                    <script src='../forms/delete/delete_approval.js'></script>
                 </div>";
        }
        ?>
    </footer>
</div>
</body>
</html>
