<?php
include_once "../assets/message_functions.php";
include_once "../assets/connection.php";
include_once "../assets/page_functions.php";

connection();
session_start();

$id_user = "";

if (!isset($_GET["id_user"]) || empty($_GET["id_user"])) {
    if (isset($_SESSION["id_user"])){
        $_GET["id_user"] = $_SESSION["id_user"];
    } else {
        header("location: ../index.php?id_page=5&offset=0");
    }
}

$id_user = validateIdUserMessageParam(strval($_GET["id_user"]));

$error = ["", "", ""];

$data = mysqli_fetch_array(getUserDetails($id_user));

if ($data) {
    $name = $data["name"];
    $surname = $data["surname"];
    $username = $data["username"];
    $role = $data["role"];
} else {
    $name = "";
    $surname = "";
    $role = "";
    $username = "";
}

if (isset($_POST["save"])) {
    $error = updateUser($id_user);

    $name = htmlspecialchars($_POST["name"]);
    $surname = htmlspecialchars($_POST["surname"]);
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
    <title><?php
        if ($data){
            echo "$name $surname";
        } else {
            echo "Not existing user";
        }
        ?>
        </title>
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
            <?php
                if ($data) {
                    echo "
                        <div class='role_block'>
                            <label class='main_label' id='info_label'>
                                <span class='main_label_text'>Personal information:</span>
                            </label>

                            <div class='role'>
                                <label class='container'>Administrator";
                                     if ($role == 'admin') {
                                        echo "<input type='radio' name='role' checked disabled>";
                                    } else {
                                         echo "<input type='radio' name='role' disabled>";
                                     }
                                       echo "    
                                    <span class='checkmark'></span>
                                </label>
            
                                <label class='container'>User";
                                     if ($role != "admin") {
                                        echo "<input type='radio' name='role' value='user' checked disabled>";
                                    } else {
                                         echo "<input type='radio' name='role' value='user' disabled>";
                                     }
                                      echo"    
                                    <span class='checkmark'></span>
                                </label>";

                                if (!empty($_SESSION["admin"])) {
                                    echo "<a href='../assets/change_role.php?id_user=$id_user&role=$role'
                                    class='profile_button' id='change_role'>Change role</a>";
                                }
                                if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                                    echo " <button type='button' id='change_info'>i</button>";
                                }
                            echo "</div>
                        </div>
            
                        <div class='personal_info_block'>
                            <div class='user_block'>
                                <form action='profile.php?id_user=$id_user' method='post'>
                                    <label>Name:
                                        <input type='text' class='info_text' name='name' id='edit_name'
                                               value='$name'
                                               minlength='2' maxlength='100' readonly required>
                                    </label>
                                    <div class='response'>$error[0]</div>
            
                                    <label>Surname:
                                        <input type='text' class='info_text' name='surname' id='edit_surname'
                                               value='$surname' minlength='2' maxlength='100' readonly required>
                                    </label>
                                    <div class='response'>$error[1]</div>
            
                                    <label>Username:
                                        <input type='text' class='info_text' name='username' id='edit_username'
                                               value='$username' minlength='4' maxlength='100' readonly required>
                                    </label>
                                    <div class='response'>$error[2]</div>
            
                                    <label id='edit_submit'></label>
                                </form> ";

                            if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                                echo "
                                    <div class='personal_link'>
                                        <button class='profile_button' id='edit_profile'>Edit my profile</button>
                                        <script src='edit_profile.js'></script>
                                        
                                        <a href='../forms/form/change_password.php' class='profile_button'>Change password</a>
                                        <a href='../forms/form/change_email.php' class='profile_button'>Change email</a>
                                        <a href='../forms/form/add_update_message.php' class='profile_button' id='add_message_button'>Add article</a>
                                    </div>";
                            }
                            echo "</div>";

                        if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                            echo "<div id='change_role_text'></div>";
                            echo "<script src='change_role_info.js'></script>";
                        }
                        echo "</div>

                        <div class='articles_link'>
                            <a id='go_to_messages'
                               href='user_messages.php?id_user=$id_user&offset=0'><span class='profile_button'>";
                            if (!empty($_SESSION["id_user"]) && ($_SESSION["id_user"] == $id_user)) {
                                echo "Go to my articles";
                            } else {
                                echo "Go to user articles";
                            }
                            echo " </span>
                            </a>
                        </div>";
            } else {
                echo "<span class='no_messages'>This user does not exist or was deleted :(</span>";
            }
            ?>
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
