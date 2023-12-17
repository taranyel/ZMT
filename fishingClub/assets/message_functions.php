<?php
include_once "user_functions.php";

/**
 * <p>The <b>addMessage()</b> function gets and validates article data from <b>$_POST</b> variable.
 *   Then if validation successfully passed, function adds new article to the database.</p>
 * @return array <p>
 *  The <b>addMessage()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function addMessage(): array
{
    global $connection;
    $title = mysqli_real_escape_string($connection, $_POST["title"]);
    $message = mysqli_real_escape_string($connection, $_POST["message"]);
    $date = date("Y-m-d");
    $id_user = $_SESSION["id_user"];
    $id_page = $_POST["page"];

    $flag = true;
    $error = isMessageValid($title, $message);
    $error[2] = "";

    for ($i = 0; $i < 2; $i++) {
        if ($error[$i] != "") {
            $flag = false;
        }
    }

    if (!$flag) {
        return $error;
    } else {
        $query = "INSERT INTO message(id_user, id_page, name, content, date) VALUES('$id_user', '$id_page', '$title', '$message', '$date')";
        mysqli_query($connection, $query);

        if (!empty($_FILES["image"]["name"])) {
            $id_message = getLastAddedMessage();

            if (!addMessageImage($id_message)) {
                $error[2] = "File is not an image!";
                return $error;
            }
        }
    }
    return [];
}

/**
 * <p>The <b>addMessageImage()</b> function gets and validates image data from <b>$_FILES</b> variable.
 *    Then if validation successfully passed, function adds new image file name to the server file system and to the
 * database with helper <b>addImageToMessage</b> function. Because image files can have the same name, function
 * also adds article id, to which image is related, at the beginning of the image file name.</p>
 * @param int $id_message <p>is given article id</p>
 * @return bool <p>
 *Returns <i>true</i> if image file validation passed successfully.</p>
 */
function addMessageImage(int $id_message): bool
{
    global $connection;

    $imageName = mysqli_real_escape_string($connection, $_FILES["image"]["name"]);
    $imageType = $_FILES["image"]["type"];

    if (!str_starts_with($imageType, "image")) {
        return false;
    }

    $target_dir = "../../message/message_images/";
    $target_file = $target_dir . $id_message . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $image = $id_message . $imageName;

    addImageToMessage($id_message, $image);
    return true;
}

/**
 * <p>The <b>addImageToMessage()</b> adds new image to the article in the database.</p>
 * @param int $id_message <p>is given article id, to which image must be added.</p>
 * @param $image <p>is given image file name.</p>
 * @return void
 */
function addImageToMessage(int $id_message, $image): void
{
    global $connection;
    $query = "UPDATE message SET image = '$image' WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>getLastAddedMessage()</b> function finds the last added article to the database.
 * The last added article definitely has the biggest id.</p>
 * @return int <p>
 *     Returns id of the last added article.
 * </p>
 */
function getLastAddedMessage(): int
{
    global $connection;
    $query = "SELECT MAX(id_message) AS id_message FROM message";
    return mysqli_fetch_array($connection, $query)["id_message"];
}

/**
 * <p>The <b>getAuthor()</b> function finds the author of the given article.</p>
 * @param int $id_message <p>is given article id.</p>
 * @return int <p>
 *     Returns id of the author of the given article.
 * </p>
 */
function getAuthor(int $id_message): int
{
    global $connection;
    $query = "SELECT id_user from message WHERE id_message = '$id_message'";
    return mysqli_fetch_array(mysqli_query($connection, $query))["id_user"];
}

/**
 * <p>The <b>getMessageByUser()</b> function finds 2 articles from offset value, written by the given user.</p>
 * @param int $id_user <p>is given user id.</p>
 * @param $offset <p>is offset value, from which articles will be taken from database.</p>
 * @return int <p>
 *     Returns amount of articles written by the given user.
 * </p>
 */
function getMessageByUser(int $id_user, $offset): int
{
    global $connection;
    $query = "SELECT * from message WHERE id_user = '$id_user' ORDER BY date DESC LIMIT 2 OFFSET $offset";
    $result = mysqli_query($connection, $query);

    fillMessageDetails($result, "byUser");

    return mysqli_num_rows($result);
}

/**
 * <p>The <b>getAmountOfAllMessagesByUser()</b> function finds all articles, written by the given user.</p>
 * @param int $id_user <p>is given user id.</p>
 * @return int <p>
 *      Returns amount of articles written by the given user.
 *  </p>
 */
function getAmountOfAllMessagesByUser(int $id_user): int
{
    global $connection;
    $query = "SELECT * from message WHERE id_user = '$id_user'";
    $result =  mysqli_query($connection, $query);

    return mysqli_num_rows($result);
}

/**
 * <p>The <b>getMessageByPage()</b> function finds 2 articles from offset value, displayed oh the given page.</p>
 * @param int $id_page <p>is given page id.</p>
 * @param $offset <p>is offset value, from which articles will be taken from database.</p>
 * @return int <p>
 *      Returns amount of articles displayed on the given page.
 *  </p>
 */
function getMessageByPage(int $id_page, $offset): int
{
    global $connection;
    $query = "SELECT * from message WHERE id_page = '$id_page' ORDER BY date DESC LIMIT 2 OFFSET $offset";
    $result =  mysqli_query($connection, $query);

    fillMessageDetails($result, "byPage");

    return mysqli_num_rows($result);
}

/**
 * <p>The <b>getAmountOfAllMessagesByPage()</b> function finds all articles, displayed oh the given page.</p>
 * @param int $id_page <p>is given page id.</p>
 * @return int <p>
 *       Returns amount of articles displayed on the given page.
 *   </p>
 */
function getAmountOfAllMessagesByPage(int $id_page): int
{
    global $connection;
    $query = "SELECT * from message WHERE id_page = '$id_page'";
    $result =  mysqli_query($connection, $query);

    return mysqli_num_rows($result);
}

/**
 * <p> The <b>fillMessageDetails()</b> collects all data about given articles
 * and displays them with helper <b>showMessage()</b> function.</p>
 * @param mysqli_result $result <p>is list of articles.</p>
 * @param $flag <p>has <i>byUser</i> or<i>byPage</i> value.</p>
 * @return void
 */
function fillMessageDetails(mysqli_result $result, $flag): void
{
    $count = mysqli_num_rows($result);
    if ($count == null) {
        echo "<span class='no_messages'>It seems empty here :(</span>";
        return;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row["name"];
        $message = $row["content"];
        $date = $row["date"];
        $id_message = $row["id_message"];
        $image = $row["image"];

        $id_user = getAuthor($id_message);
        $user_details = mysqli_fetch_array(getUserDetails($id_user));
        $username = $user_details["username"];

        echo "<div class='message'>";
        showMessage($image, $id_user, $id_message, $message, $date, $title, $username, $flag);
        echo "</div>";
    }
}

/**
 * <p>The <b>showMessage()</b> function displays given article on the page.</p>
 * @param $image <p>is given image file name.</p>
 * @param int $id_user <p>is id user, who  wrote given article.</p>
 * @param int $id_message <p>is given article id.</p>
 * @param $message <p>is given article text.</p>
 * @param $date <p>is date, when article was wrote.</p>
 * @param $title <p>is article title.</p>
 * @param $username <p>is username of the user, who wrote article.</p>
 * @param $flag <p>has <i>byUser</i> (article will display at the <i>user articles</i> block)
 * or <i>byPage</i> (article will display at the normal page) value.</p>
 * @return void
 */
function showMessage($image, int $id_user, int $id_message, $message, $date, $title, $username, $flag): void
{
    echo "<div class='prev_text'>
            <div class='label'>
                <h2 class='label_text'>";
    echo htmlspecialchars($title);
    echo "</h2>";

    if ($flag == "byUser") {
        echo "<a href='profile.php?id_user=$id_user' class='username'>";
        echo htmlspecialchars($username);
        echo "</a>";

    } elseif ($flag == "byPage") {
        echo "<a href='profile/profile.php?id_user=$id_user' class='username'>";
        echo htmlspecialchars($username);
        echo "</a>";
    }
    echo "<span class='date'>$date</span>
            </div>";

    echo "<div class='message_content'>";

    echo "<div class='message_text'>";

    $str = str_replace('\r\n', PHP_EOL, htmlspecialchars($message));
    echo "<pre>";
    if ($image != null) {
        if ($flag == "byUser") {
            echo "<img src='../message/message_images/$image' alt='$title'>";
        } elseif ($flag == 'byPage') {
            echo "<img src='message/message_images/$image' alt='$title'>";
        }
    }
    echo "$str</pre>";

    if ($flag == "byUser") {
        echo "<a href='../message/message.php?id_message=$id_message&id_user=$id_user'>Read more...</a>";
    } elseif ($flag == "byPage") {
        echo "<a href='message/message.php?id_message=$id_message&id_user=$id_user'>Read more...</a>";
    }
    echo "</div>
        </div>
        </div>";
}

/**
 * <p>The <b>showByParagraphs()</b> function displays article in it original form.</p>
 * @param $message <p>is given article text.</p>
 * @return void
 */
function showByParagraphs($message): void
{
    $str = str_replace('\r\n', PHP_EOL, htmlspecialchars($message));
    echo "<pre>$str</pre>";
}

/**
 * <p>The <b>getMessageDetails()</b> function gives all article data.</p>
 * @param int $id_message <p>is given article.</p>
 * @return mysqli_result <p>
 *     Returns all article data.
 * </p>
 */
function getMessageDetails(int $id_message): mysqli_result
{
    global $connection;
    $query = "SELECT * from message WHERE id_message = '$id_message'";
    return mysqli_query($connection, $query);
}

/**
 * <p>The <b>updateTitle()</b> function updates article's title in the database.</p>
 * @param string $newTitle <p>is a new article's title, which function must update in database.</p>
 * @param int $id_message <p>is current article's unique id.</p>
 * @return void
 */
function updateTitle(string $newTitle, int $id_message): void
{
    global $connection;
    $query = "UPDATE message SET name = '$newTitle' WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>updateContent()</b> function updates article's text in the database.</p>
 * @param string $newContent <p>is a new article's text, which function must update in database.</p>
 * @param int $id_message <p>is current article's unique id.</p>
 * @return void
 */
function updateContent(string $newContent, int $id_message): void
{
    global $connection;
    $query = "UPDATE message SET content = '$newContent' WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>updateMessagePrepare()</b> function gets and validates article's new data from <b>$_POST</b> variable.
 *  This function is used in article data updating function.</p>
 * @return array <p>
 *   The <b>updateMessagePrepare()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function updateMessagePrepare(): array
{
    global $connection;
    $newTitle = mysqli_real_escape_string($connection, $_POST["title"]);
    $error[0] = isTitleValid($newTitle);

    $newMessage = mysqli_real_escape_string($connection, $_POST["message"]);
    $error[1] = isContentValid($newMessage);

    return $error;
}

/**
 * <p>This function checks if article's new data is valid with helper function <b>updateMessagePrepare()</b> and
 *  if validation successfully passed, gets article's data from <b>$_POST</b> variable and updates it in the
 *  database with data update functions.</p>
 * @param int $id_message <p>is current article's unique id.</p>
 * @return array <p>
 *      The <b>updateMessage()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function updateMessage(int $id_message): array
{
    global $connection;

    $error = updateMessagePrepare();
    $flag = true;

    for ($i = 0; $i < 2; $i++) {
        if ($error[$i] != "") {
            $flag = false;
        }
    }

    if ($flag) {
        if (!empty($_POST["title"])) {
            updateTitle(mysqli_real_escape_string($connection, $_POST["title"]), $id_message);
        }
        if (!empty($_POST["message"])) {
            updateContent(mysqli_real_escape_string($connection, $_POST["message"]), $id_message);
        }
    } else {
        return $error;
    }

    return [];
}

/**
 * <p>The <b>deleteMessage()</b> function deletes article according to its id.</p>
 * @param int $id_message <p>is current article's unique id.</p>
 * @return void
 */
function deleteMessage(int $id_message): void
{
    global $connection;
    $query = "DELETE FROM message WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}