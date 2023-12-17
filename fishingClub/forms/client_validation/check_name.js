const name = document.getElementById("name");
const name_response = document.getElementById("name_response");
const surname = document.getElementById("surname");
const surname_response = document.getElementById("surname_response");
let name_submit = true;
let surname_submit = true;

name.addEventListener("focusout", checkName);
surname.addEventListener("focusout", checkSurname);

/**
 * <p>The <b>checkName()</b> function represents client name validation. It uses regular expressions to find out if name is valid.
 * If name is invalid, function disables form's submit button.</p>
 */
function checkName() {
    const nameRegex = /^[a-zA-Z]+$/;
    if (!nameRegex.test(name.value) && name.value.length !== 0) {
        name_response.innerHTML = "<span class='response_failed'>Only letters are allowed!</span>";
        name_submit = false;
    } else {
        name_response.innerHTML = "";
        name_submit = true;
    }
}

/**
 * <p>The <b>checkSurname()</b> function represents client surname validation. It uses regular expressions to find out if surname is valid.
 * If surname is invalid, function disables form's submit button.</p>
 */
function checkSurname() {
    const surnameRegex = /^[a-zA-Z-' ]+$/;
    if (!surnameRegex.test(surname.value) && surname.value.length !== 0) {
        surname_response.innerHTML = "<span class='response_failed'>Only letters and white space are allowed!</span>";
        surname_submit = false;
    } else {
        surname_response.innerHTML = "";
        surname_submit = true;
    }
}