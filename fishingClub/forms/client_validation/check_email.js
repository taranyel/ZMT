const email = document.getElementById("email");
email.addEventListener("focusout", checkEmail);

const url = new URL(document.location).searchParams;

function checkEmail() {
    if (url){
        if (email.value === url.get("email")){
            return;
        }
    }

    let http = new XMLHttpRequest();
    http.open("GET", "../client_validation/ajax.php?email=" + encodeURI(email.value), true);

    http.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            if (this.responseText === "invalid"){
                document.getElementById('email_response').innerHTML = "<span class='response_failed'>Email is already in use!</span>";
                submit.addEventListener("click", blockSubmit, false);
            } else {
                document.getElementById('email_response').innerHTML = "";
                submit.removeEventListener("click", blockSubmit);
            }
        }
    };
    http.send();
}

function blockSubmit(event) {
    event.preventDefault();
}