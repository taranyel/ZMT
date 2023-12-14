<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>About us</title>
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
                echo "<a href='../profile/profile.php?id_user=$_SESSION[id_user]' class='header_link' id='profile_link'>My profile</a>";
            } else {
                echo "<a href='../forms/form/login.php' class='header_link'>Log in</a>";
                echo "<a href='../forms/form/sign_up.php' class='header_link'>Sign up</a>";
            }
            ?>
            <a href='about_us.php' class='about_link'>About us</a>
            <a href='contacts.php' class='about_link'>Contacts</a>
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

        <p>About us</p>
    </div>
    <footer></footer>
</div>
</body>
</html>