// pswd_chk.js
// An example of input password checking, using the submit event

// The event handler function for password checking
function chkPasswords() {
    var init = document.getElementById("initial");
    var sec = document.getElementById("second");
    if (init.value == "") {
        alert("You did not enter a password \nPlease enter one now");
        init.focus();
        // return false to prevent submission of the form/ keep form alive
        return false;
    }
    if (init.value != sec.value) {
        alert("The two passwords you entered are not the same \nPlease re-enter both now");
        init.focus();
        // Select the text in the initial password field to facilitate
        // re-entry usability (no click to highlight needed)
        init.select();
        return false;
    } else
        return true;
}