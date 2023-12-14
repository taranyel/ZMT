const username = document.getElementById("username");
const submit = document.getElementById("form__submit");

username.addEventListener("focusout", checkUsername);

const link = new URL(document.location).searchParams;
function checkUsername(event) {
    if (link){
        if (username.value === link.get("username")){
            return;
        }
    }

    const usernameRegex = /^[a-zA-Z0-9._-]+$/;
    if (usernameRegex.test(username.value) && username.value.length > 4) {
         document.getElementById('username_response').innerHTML = "";

        let http = new XMLHttpRequest();
        http.open("GET", "../client_validation/ajax.php?username=" + encodeURI(username.value), true);
        http.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                if (this.responseText === "valid"){
                    document.getElementById('username_response').innerHTML = "<span class='response_ok'>Username is available.</span>";
                    submit.removeEventListener("click", blockSubmit);
                } else {
                    document.getElementById('username_response').innerHTML = "<span class='response_failed'>Username is already taken!</span>";
                    submit.addEventListener("click", blockSubmit, false);
                }
            }
        };
        http.send();

    } else {
        if (username.value.length !== 0){
            document.getElementById('username_response').innerHTML = "<span class='response_failed'>Only letters, digits, -, _, . are allowed!</span>";
            submit.addEventListener("click", blockSubmit, false);
        } else {
            document.getElementById('username_response').innerHTML = "";
            submit.removeEventListener("click", blockSubmit);
        }
    }
}

function blockSubmit(event) {
    event.preventDefault();
}