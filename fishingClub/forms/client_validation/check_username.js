const username = document.getElementById("username");
const username_response = document.getElementById('username_response');
let username_submit = true;

username.addEventListener("focusout", checkUsername);

/**
 * <p>The <b>checkUsername()</b> function represents client username validation. Firstly, function validates username with
 * regular expression, then if validation is successfully passed, it sends an ajax GET request
 * to the <i>ajax.php</i> file, and inners response as HTML
 * according to ajax response. Also, if username is invalid, function disables form's submit button.</p>
 */
function checkUsername() {

    const usernameRegex = /^[a-zA-Z0-9._-]+$/;

    if (usernameRegex.test(username.value)) {
        document.getElementById('username_response').innerHTML = "";

        let http = new XMLHttpRequest();
        http.open("GET", "../client_validation/ajax.php?username=" + encodeURI(username.value), true);
        http.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                if (this.responseText === "valid") {
                    username_response.innerHTML = "<span class='response_ok'>Username is available.</span>";
                    username_submit = true;
                } else {
                    username_response.innerHTML = "<span class='response_failed'>Username is already taken!</span>";
                    username_submit = false;
                }
            }
        };
        http.send();

    } else {
        if (username.value.length !== 0) {
            username_response.innerHTML = "<span class='response_failed'>Only letters, digits, -, _, . are allowed!</span>";
            username_submit = false;
        } else {
            username_response.innerHTML = "";
            username_submit = true;
        }
    }
}