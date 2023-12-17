const delete_button = document.getElementById("delete_button");
const id_user = new URLSearchParams(window.location.search).get("id_user");
delete_button.addEventListener("click", throwWarning)

/**
 * <p>Function throws confirmation warning, when user wants to delete his profile.
 * If user confirms this confirmation, function will redirect him to the <i>delete.php</i> and user's
 * profile will be deleted.</p>
 */
function throwWarning(){
    if (window.confirm("Do you really want to delete your account?")) {
        window.location.replace('../forms/delete/delete_user.php?id_user=' + id_user);
    }
}