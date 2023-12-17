const editButton = document.getElementById("edit_profile");
editButton.addEventListener("click", editProfile);

const inputs = document.querySelectorAll(".info_text");
const editSubmit = document.getElementById("edit_submit");

/**
 * <p>Function removes <i>readonly</i> attributes from HTML <i>inputs</i> to enable their editing.
 * Also function displays <b>Save</b> button to submit editing.</p>
 */
function editProfile(){
    for (let i = 0; i < inputs.length; i++){
        inputs[i].removeAttribute("readonly");

    }
    editSubmit.innerHTML = "<input type='submit' name='save' class='profile_button' id='save' value='Save'>";
}
