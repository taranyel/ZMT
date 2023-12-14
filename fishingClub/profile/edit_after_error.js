for (let i = 0; i < inputs.length; i++){
    inputs[i].removeAttribute("readonly");

}
editSubmit.innerHTML = "<input type='submit' name='save' class='profile_button' id='save' value='Save'>";