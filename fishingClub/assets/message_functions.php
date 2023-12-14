<?php
include_once "user_functions.php";
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
            $id_message = mysqli_fetch_array(getLastAddedMessage())["id_message"];

            if (!addMessageImage($id_message)) {
                $error[2] = "File is not an image!";
                return $error;
            }
        }
    }
    return [];
}

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

function addImageToMessage(int $id_message, $image): void
{
    global $connection;
    $query = "UPDATE message SET image = '$image' WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}

function getLastAddedMessage(): mysqli_result
{
    global $connection;
    $query = "SELECT MAX(id_message) AS id_message FROM message";
    return mysqli_query($connection, $query);
}

function getAuthor(int $id_message): mysqli_result
{
    global $connection;
    $query = "SELECT * from message WHERE id_message = '$id_message'";
    return mysqli_query($connection, $query);
}

function getMessageByUser(int $id_user, $offset): void
{
    global $connection;
    $query = "SELECT * from message WHERE id_user = '$id_user' ORDER BY date DESC LIMIT 2 OFFSET $offset";
    $result = mysqli_query($connection, $query);

    fillMessageDetails($result, "byUser");
}

function getAmountOfAllMessagesByUser(int $id_user): int
{
    global $connection;
    $query = "SELECT * from message WHERE id_user = '$id_user'";
    $result =  mysqli_query($connection, $query);

    return mysqli_num_rows($result);
}

function getMessageByPage(int $id_page, $offset): void
{
    global $connection;
    $query = "SELECT * from message WHERE id_page = '$id_page' ORDER BY date DESC LIMIT 2 OFFSET $offset";
    $result =  mysqli_query($connection, $query);

    fillMessageDetails($result, "byPage");
}

function getAmountOfAllMessagesByPage(int $id_page): int
{
    global $connection;
    $query = "SELECT * from message WHERE id_page = '$id_page'";
    $result =  mysqli_query($connection, $query);

    return mysqli_num_rows($result);
}

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

        $id_user = mysqli_fetch_array(getAuthor($id_message))["id_user"];
        $user_details = mysqli_fetch_array(getUserDetails($id_user));
        $username = $user_details["username"];

        echo "<div class='message'>";
        showMessage($image, $id_user, $id_message, $message, $date, $title, $username, $flag);
        echo "</div>";
    }
}

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

function showByParagraphs($message): void
{
    $str = str_replace('\r\n', PHP_EOL, htmlspecialchars($message));
    echo "<pre>$str</pre>";
}

function getMessageDetails(int $id_message): mysqli_result
{
    global $connection;
    $query = "SELECT * from message WHERE id_message = '$id_message'";
    return mysqli_query($connection, $query);
}

function updateTitle(string $newTitle, int $id_message): void
{
    global $connection;
    $query = "UPDATE message SET name = '$newTitle' WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}

function updateContent(string $newContent, int $id_message): void
{
    global $connection;
    $query = "UPDATE message SET content = '$newContent' WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}

function updateMessagePrepare(): array
{
    global $connection;
    $newTitle = mysqli_real_escape_string($connection, $_POST["title"]);
    $error[0] = isTitleValid($newTitle);

    $newMessage = mysqli_real_escape_string($connection, $_POST["message"]);
    $error[1] = isContentValid($newMessage);

    return $error;
}

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

function deleteMessage(int $id_message): void
{
    global $connection;
    $query = "DELETE FROM message WHERE id_message = '$id_message'";
    mysqli_query($connection, $query);
}