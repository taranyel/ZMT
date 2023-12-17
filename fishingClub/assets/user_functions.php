<?php
include "server_validation.php";

/**
 * <p>The <b>addUser()</b> function gets and validates user data from <b>$_POST</b> variable.
 *  Then if validation successfully passed, function adds new user to the database.</p>
 * @return array <p>
 * The <b>addUser()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function addUser(): array
{
    global $connection;
    $name = mysqli_real_escape_string($connection, $_POST["name"]);
    $surname = mysqli_real_escape_string($connection, $_POST["surname"]);
    $email = mysqli_real_escape_string($connection, $_POST["email"]);
    $username = mysqli_real_escape_string($connection, $_POST["username"]);
    $password = password_hash(mysqli_real_escape_string($connection, $_POST["password"]), PASSWORD_DEFAULT);

    $flag = true;
    $error = isUserValid($name, $surname, $email, $username);
    $error[4] = "";
    $error[5] = "";

    for ($i = 0; $i < 4; $i++) {
        if ($error[$i] != "") {
            $flag = false;
        }
    }

    if (($error[3] == "") && !isUsernameAvailable($username)) {
        $error[3] = "Username is already taken!";
    }

    if (($error[2] == "") && !isEmailAvailable($email)) {
        $error[2] = "Email is already in use!";
    }

    $password_error = changePasswordPrepare();
    $error[4] = $password_error[0];
    $error[5] = $password_error[1];

    if (!$flag) {
        return $error;
    } else {
        $query = "INSERT INTO user(email, username, password, name, surname, role) VALUES('$email', '$username', '$password', '$name', '$surname', 'user')";
        mysqli_query($connection, $query);
    }

    return [];
}

/**
 * <p>The <b>updateName()</b> function updates user's name in the database.</p>
 * @param string $newName <p>is a new user's name, which function must update in database.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return void
 */
function updateName(string $newName, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET name = '$newName' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>updateSurname()</b> function updates user's surname in the database.</p>
 * @param string $newSurname <p>is a new user's surname, which function must update in database.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return void
 */
function updateSurname(string $newSurname, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET surname = '$newSurname' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>updateEmail()</b> function updates user's email in the database.</p>
 * @param string $newEmail <p>is a new user's email, which function must update in database.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return void
 */
function updateEmail(string $newEmail, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET email = '$newEmail' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>updateUsername()</b> function updates user's username in the database.</p>
 * @param string $newUsername <p>is a new user's username, which function must update in database.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return void
 */
function updateUsername(string $newUsername, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET username = '$newUsername' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>updatePassword()</b> function updates user's password in the database.</p>
 * @param string $newPassword <p>is a new user's password, which function must update in database.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return void
 */
function updatePassword(string $newPassword, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET password = '$newPassword' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

/**
 * <p>This function checks if user's new email is valid with and if validation successfully passed,
 * gets user email from <b>$_GET</b> variable and updates it in the
 *  database with function <b>updateEmail()</b>.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return string <p>
 *    The <b>changePasswordPrepare()</b> function must return an email error, which was discovered during validation.</p>
 */
function changeEmail(int $id_user): string
{
    global $connection;
    $user = mysqli_fetch_array(getUserDetails($_SESSION["id_user"]));

    $newEmail = mysqli_real_escape_string($connection, $_GET["email"]);
    $error = isEmailValid($newEmail);
    if (($newEmail != $user["email"]) && ($error == "") && !isEmailAvailable($newEmail)) {
        $error = "Email is already in use!";
    }

    if ($error == "") {
        if (!empty($_GET["email"])) {
            updateEmail(mysqli_real_escape_string($connection, $_GET["email"]), $id_user);
        }
    }

    return $error;
}

/**
 * <p>The <b>updateUserPrepare()</b> function gets and validates user's new data (except password) from <b>$_POST</b> variable.
 * This function is used in user info updating function.</p>
 * @return array <p>
 *  The <b>updateUserPrepare()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function updateUserPrepare(int $id_user): array
{
    global $connection;
    $user = mysqli_fetch_array(getUserDetails($id_user));

    $error[0] = isNameValid(mysqli_real_escape_string($connection, $_POST["name"]));
    $error[1] = isNameValid(mysqli_real_escape_string($connection, $_POST["surname"]));

    $newUsername = mysqli_real_escape_string($connection, $_POST["username"]);
    $error[2] = isUsernameValid($newUsername);
    if (($newUsername != $user["username"]) && ($error[2] == "") && !isUsernameAvailable($newUsername)) {
        $error[2] = "Username is already taken!";
    }

    return $error;
}

/**
 * <p>The <b>changePasswordPrepare()</b> function gets and validates user's new passwords from <b>$_POST</b> variable.
 * This function is used in user password updating method.</p>
 * @return array <p>
 *   The <b>changePasswordPrepare()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function changePasswordPrepare(): array
{
    global $connection;
    $newPassword = mysqli_real_escape_string($connection, $_POST["password"]);
    $error[0] = isPasswordValid($newPassword);
    $error[1] = "";

    if (!empty($_POST["confirm_password"])) {
        $newPasswordConfirm = mysqli_real_escape_string($connection, $_POST["confirm_password"]);
        if ($newPassword != $newPasswordConfirm) {
            $error[1] = "Passwords must be equal!";
        }
    } else {
        $error[1] = "Passwords must be equal!";
    }

    return $error;
}

/**
 * <p>This function checks if user's new password is valid with helper function <b>changePasswordPrepare()</b> and
 * if validation successfully passed, gets user password from <b>$_POST</b> variable and updates it in the
 * database with function <b>updatePassword()</b>.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return array <p>
 *    The <b>changePassword)</b> function must return an array of errors, which were discovered during validation.</p>
 */
function changePassword(int $id_user): array
{
    global $connection;

    $error = changePasswordPrepare();
    $flag = true;

    for ($i = 0; $i < 2; $i++) {
        if ($error[$i] != "") {
            $flag = false;
        }
    }

    if ($flag) {
        if (!empty($_POST["password"])) {
            $newPassword = password_hash(mysqli_real_escape_string($connection, $_POST["password"]), PASSWORD_DEFAULT);
            updatePassword($newPassword, $id_user);
        }
    } else {
        return $error;
    }
    return [];
}

/**
 * <p>This function checks if user's new data is valid with helper function <b>updateUserPrepare()</b> and
 * if validation successfully passed, gets user's data from <b>$_POST</b> variable and updates it in the
 * database with data update functions.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return array <p>
 *     The <b>updateUser()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function updateUser(int $id_user): array
{
    global $connection;

    $error = updateUserPrepare($id_user);
    $flag = true;

    for ($i = 0; $i < 3; $i++) {
        if ($error[$i] != "") {
            $flag = false;
        }
    }

    if ($flag) {
        if (!empty($_POST["name"])) {
            updateName(mysqli_real_escape_string($connection, $_POST["name"]), $id_user);
        }
        if (!empty($_POST["surname"])) {
            updateSurname(mysqli_real_escape_string($connection, $_POST["surname"]), $id_user);
        }
        if (!empty($_POST["username"])) {
            updateUsername(mysqli_real_escape_string($connection, $_POST["username"]), $id_user);
        }

    } else {
        return $error;
    }

    return [];
}

/**
 * <p>The <b>deleteUser()</b> function deletes user articles and user according to its id.</p>
 * @param int $id_user <p>is current user's unique id.</p>
 * @return void
 */
function deleteUser(int $id_user): void
{
    global $connection;
    $query = "DELETE FROM message WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
    $query = "DELETE FROM user WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

/**
 * <p>The <b>login()</b> function checks if user entered valid data to login form.
 * If validation passed, function logs in current user and fills <b>$_SESSION["id_user"]</b> parameter.</p>
 * @return array <p>
 *      The <b>login()</b> function must return an array of errors, which were discovered during validation.</p>
 */
function login(): array
{
    global $connection;
    $email = mysqli_real_escape_string($connection, $_POST["email"]);
    $password = mysqli_real_escape_string($connection, $_POST["password"]);

    $query = "SELECT id_user, password, role FROM user WHERE email = '$email'";
    $result = mysqli_query($connection, $query);
    $data = mysqli_fetch_array($result);

    $count = mysqli_num_rows($result);

    $error[0] = "";
    $error[1] = "";

    if ($count == 0) {
        $error[0] = "Wrong email!";
    } else {
        if (!password_verify($password, $data["password"])) {
            $error[1] = "Wrong password!";
        }
    }

    if (strlen($email) == 0) {
        $error[0] = "You must fill this field!";
    }

    if (strlen($password) == 0) {
        $error[1] = "You must fill this field!";
    }

    if ($error[0] != "" || $error[1] != "") {
        return $error;
    }

    if ($data["role"] == "admin") {
        $_SESSION["admin"] = true;
    }

    $_SESSION["id_user"] = $data["id_user"];

    return [];
}

/**
 * <p>The <b>isUsernameAvailable()</b> function checks if given username is unique.</p>
 * @param string $username <p>is username entered by user.</p>
 * @return bool <p>
 *     Returns <i>true</i> if given username is unique.</p>
 */
function isUsernameAvailable(string $username): bool
{
    global $connection;

    $username = mysqli_real_escape_string($connection, $username);

    $query = "select * from user where username ='$username'";
    $result = mysqli_query($connection, $query);

    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return false;
    }
    return true;
}

/**
 * <p>The <b>isEmailAvailable()</b> function checks if given email is not already in database (in use).</p>
 * @param string $email <p>is email entered by user.</p>
 * @return bool <p>
 *      Returns <i>true</i> if given email is not already in database.</p>
 */
function isEmailAvailable(string $email): bool
{
    global $connection;

    $email = mysqli_real_escape_string($connection, $email);

    $query = "select * from user where email ='$email'";
    $result = mysqli_query($connection, $query);

    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return false;
    }
    return true;
}

/**
 * @param int $id_user <p>is current user's unique id.</p>
 * @return mysqli_result <p>
 * The <b>getUserDetails()</b> function returns all user data from database.</p>
 */
function getUserDetails(int $id_user): mysqli_result
{
    global $connection;
    $query = "SELECT * from user WHERE id_user = '$id_user'";
    return mysqli_query($connection, $query);
}