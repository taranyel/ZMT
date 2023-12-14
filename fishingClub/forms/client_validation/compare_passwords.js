const password = document.getElementById("password");
const conf_password = document.getElementById("confirm_password");

if (password && conf_password) {
    password.addEventListener("input", comparePasswords);
    conf_password.addEventListener("input", comparePasswords);
}

function comparePasswords() {
    if (password.value !== conf_password.value) {
        document.getElementById('conf_password_response').innerHTML = "<span class='response_failed'>Passwords are not equal.</span>";
        document.getElementById('form__submit').disabled = true;
    } else if (password.value.length === 0 && conf_password.value.length === 0){
        document.getElementById('conf_password_response').innerHTML = "";
        document.getElementById('form__submit').disabled = false;
    }
    else {
        document.getElementById('conf_password_response').innerHTML = "<span class='response_ok'>Passwords are equal.</span>";
        document.getElementById('form__submit').disabled = false;
    }
}