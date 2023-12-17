const password = document.getElementById("password");
const conf_password = document.getElementById("confirm_password");
const password_response = document.getElementById("password_response");
const conf_password_response = document.getElementById("conf_password_response");
const submit_button_password = document.getElementById("form__submit");
let password_submit = true;

password.addEventListener("input", comparePasswords);
conf_password.addEventListener("input", comparePasswords);

/**
 * <p>The <b>comparePasswords()</b> function represents client password validation.
 * It connects regular password field and confirmed password filed, checks if their values are equal and valid.
 * If password or confirmed password is invalid, function disables form's submit button.</p>
 */
function comparePasswords() {
    let digit = false;
    let lowerCase = false;
    let upperCase = false;

    if (password.value === "") {
        password_response.innerHTML = "";

    } else {
        let passwordRegex = /^(?=.*[0-9])[A-Za-z\d@.#$!%*?&]{1,50}$/;
        if (!passwordRegex.test(password.value)) {
            password_response.innerHTML = "<span class='response_failed'>At least 1 number is required!</span>";
        } else {
            digit = true;
        }

        passwordRegex = /^(?=.*[A-Z])[A-Za-z\d@.#$!%*?&]{1,50}$/;
        if (!passwordRegex.test(password.value)) {
            password_response.innerHTML = "<span class='response_failed'>At least 1 capital letter is required!</span>";
        } else {
            upperCase = true;
        }

        passwordRegex = /^(?=.*[a-z])[A-Za-z\d@.#$!%*?&]{1,50}$/;
        if (!passwordRegex.test(password.value)) {
            password_response.innerHTML = "<span class='response_failed'>At least 1 lowercase letter is required!</span>";
        } else {
            lowerCase = true;
        }
    }

    if (digit && lowerCase && upperCase) {
        password_response.innerHTML = "";
        password_submit = true;
        submit_button_password.removeEventListener("click", blockSubmit);
    } else {
        submit_button_password.addEventListener("click", blockSubmit, false);
    }

    if (password.value !== conf_password.value) {
        conf_password_response.innerHTML = "<span class='response_failed'>Passwords are not equal.</span>";
        submit_button_password.addEventListener("click", blockSubmit, false);
        password_submit = false;

    } else if (password.value.length === 0 && conf_password.value.length === 0) {
        conf_password_response.innerHTML = "";
        submit_button_password.addEventListener("click", blockSubmit, false);
        password_submit = false;

    } else {
        conf_password_response.innerHTML = "<span class='response_ok'>Passwords are equal.</span>";
        submit_button_password.removeEventListener("click", blockSubmit);
        password_submit = true;
    }
}

function blockSubmit(event) {
    event.preventDefault();
}