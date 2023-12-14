const change_info_button = document.getElementById("change_info");
const change_role_info = document.getElementById("change_role_text");

change_info_button.addEventListener("click", show_hide_text);

function show_hide_text() {
    const info = document.getElementById("info");
    if (info){
        info.remove();
    } else {
        change_role_info.innerHTML = "<span id='info'>If your role has been changed, you must log out and log in again for your new rights to take effect.</span>";
    }
}