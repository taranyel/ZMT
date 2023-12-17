const email = document.getElementById("email");
const submit_button_email = document.getElementById("form__submit");
let email_submit = true;

email.addEventListener("focusout", checkEmail);

/**
 * <p>The <b>checkEmail()</b> function represents client email validation. It sends an ajax GET request
 * to the <i>ajax.php</i> file, and inners response as HTML
 * according to ajax response. Also, if email is invalid, function disables form's submit button.</p>
 */
function checkEmail() {

    let http = new XMLHttpRequest();
    http.open("GET", "../client_validation/ajax.php?email=" + encodeURI(email.value), true);

    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            if (this.responseText === "invalid" && email.value.length !== 0) {
                document.getElementById('email_response').innerHTML = "<span class='response_failed'>Email is already in use!</span>";
                email_submit = false;
                if (submit_button_email){
                    submit_button_email.addEventListener("click", blockSubmit, false);
                }
            } else {
                document.getElementById('email_response').innerHTML = "";
                email_submit = true;
                if (submit_button_email) {
                    submit_button_email.removeEventListener("click", blockSubmit);
                }
            }
        }
    };
    http.send();
}

function blockSubmit(event) {
    event.preventDefault();
}