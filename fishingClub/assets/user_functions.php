<?php
include "server_validation.php";
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

function updateName(string $newName, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET name = '$newName' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

function updateSurname(string $newSurname, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET surname = '$newSurname' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

function updateEmail(string $newEmail, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET email = '$newEmail' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

function updateUsername(string $newUsername, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET username = '$newUsername' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

function updatePassword(string $newPassword, int $id_user): void
{
    global $connection;
    $query = "UPDATE user SET password = '$newPassword' WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

function updateUserPrepare(): array
{
    global $connection;
    $user = mysqli_fetch_array(getUserDetails($_SESSION["id_user"]));

    $error[0] = isNameValid(mysqli_real_escape_string($connection, $_POST["name"]));
    $error[1] = isNameValid(mysqli_real_escape_string($connection, $_POST["surname"]));

    $newEmail = mysqli_real_escape_string($connection, $_POST["email"]);
    $error[2] = isEmailValid($newEmail);
    if (($newEmail != $user["email"]) && ($error[2] == "") && !isEmailAvailable($newEmail)) {
        $error[2] = "Email is already in use!";
    }

    $newUsername = mysqli_real_escape_string($connection, $_POST["username"]);
    $error[3] = isUsernameValid($newUsername);
    if (($newUsername != $user["username"]) && ($error[3] == "") && !isUsernameAvailable($newUsername)) {
        $error[3] = "Username is already taken!";
    }

    return $error;
}

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

function updateUser(int $id_user): array
{

    global $connection;

    $error = updateUserPrepare();
    $flag = true;

    for ($i = 0; $i < 4; $i++) {
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
        if (!empty($_POST["email"])) {
            updateEmail(mysqli_real_escape_string($connection, $_POST["email"]), $id_user);
        }
        if (!empty($_POST["username"])) {
            updateUsername(mysqli_real_escape_string($connection, $_POST["username"]), $id_user);
        }

    } else {
        return $error;
    }

    return [];
}

function deleteUser(int $id_user): void
{
    global $connection;
    $query = "DELETE FROM message WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
    $query = "DELETE FROM user WHERE id_user = '$id_user'";
    mysqli_query($connection, $query);
}

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

    if (strlen($email) == 0){
        $error[0] = "You must fill this field!";
    }

    if (strlen($password) == 0){
        $error[1] = "You must fill this field!";
    }

    if ($error[0] != "" || $error[1] != ""){
        return $error;
    }

    if ($data["role"] == "admin") {
        $_SESSION["admin"] = true;
    }

    $_SESSION["id_user"] = $data["id_user"];

    return [];
}

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

function getUserDetails(int $id_user): mysqli_result
{
    global $connection;
    $query = "SELECT * from user WHERE id_user = '$id_user'";
    return mysqli_query($connection, $query);
}