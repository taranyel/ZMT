const form_submit = document.getElementById("form__submit");
name.addEventListener("focusout", submit);
surname.addEventListener("focusout", submit);
email.addEventListener("focusout", submit);
username.addEventListener("focusout", submit);
password.addEventListener("focusout", submit);
conf_password.addEventListener("focusout", submit);

/**
 * <p>The <b>submit()</b> function collects all validation data from other validating functions and disables form submit
 * button if even only one function returned <i>false</i>.</p>
 */
function submit(){
    if (!name_submit || !username_submit || !email_submit || !username_submit || !password_submit){
        form_submit.addEventListener("click", blockSubmit, false);
    } else {
        form_submit.removeEventListener("click", blockSubmit);
    }        console.log(name_submit, surname_submit, email_submit, username_submit, password_submit);

}

/**
 * Function disables form submit button.
 * @param event <p>is event handler.</p>
 */
function blockSubmit(event) {
    event.preventDefault();
}