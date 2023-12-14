const delete_button = document.getElementById("delete_button");
const id_user = new URLSearchParams(window.location.search).get("id_user");
delete_button.addEventListener("click", throwWarning)

function throwWarning(){
    if (window.confirm("Do you really want to delete your account?")) {
        window.location.replace('../forms/delete/delete_user.php?id_user=' + id_user);
    }
}