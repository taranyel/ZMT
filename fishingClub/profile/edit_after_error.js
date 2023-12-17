continueEditing()

/**
 * <p>Function removes <i>readonly</i> attributes from HTML <i>inputs</i> to enable their editing after profile page was reloaded.
 * Also function displays <b>Save</b> button to submit editing.</p>
 * */
function continueEditing(){
    for (let i = 0; i < inputs.length; i++){
        inputs[i].removeAttribute("readonly");

    }
    editSubmit.innerHTML = "<input type='submit' name='save' class='profile_button' id='save' value='Save'>";
}
