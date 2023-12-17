<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>About us</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../page.css">
    <link rel="stylesheet" href="../message/message.css">
    <link rel="stylesheet" href="about_us.css">
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
                <div class="message_content_prev">
                    <h1>Description</h1>
                    <p> We are glad to welcome you to our Czech fishing forum! Here we learn something new and share
                        our knowledge about news from the Czech fishing world, updates in fishing rules, the best
                        fishing locations and the most interesting stories and photos of our users!
                        The main page is created for fishing news, which ar published
                        by the forum administrator, you can publish your articles in any category except this one. In
                        your profile you will find your information and your articles. You can edit them if you want.
                        You will find all of the above in the sections of the site on
                        the left. As a regular user you can both read and write your own articles! Just don't forget to
                        adhere to the strict rules of our forum!
                        <span>---></span>
                    </p>
                    <div class="contact">
                        <p>
                            Our contact:
                        </p>
                        <p>
                            elizavetataranova04@gmail.com - Yelyzaveta, primary forum administrator
                        </p>
                    </div>
                </div>

                <div class="message_content_prev">
                    <h1>Forum rules</h1>
                    <ul>
                        <li>
                            It is forbidden to insult other users.
                        </li>
                        <li>
                            Your articles must not contain insults, obscenities, and must be written in a polite manner.
                        </li>
                        <li>
                            Your articles must contain information exclusively on the topic of the category in which you
                            are
                            writing.
                        </li>
                        <li>
                            Discussion of politics, religion and other dangerous topics is prohibited.
                        </li>
                        <li>
                            It is prohibited to post photos that do not correspond to the topic of the category being
                            discussed.
                        </li>
                    </ul>
                    <p class="warning">
                        For violating any of the rules, your account will be deleted!!!
                    </p>
                </div>
            </div>
        </div>
    </div>
    <footer></footer>
</div>
</body>
</html>