document.addEventListener("DOMContentLoaded", () => {
  const form        = document.querySelector("form");
  const nameEl      = document.getElementById("name");
  const emailEl     = document.getElementById("email");
  const startEl     = document.getElementById("startDate");
  const expEl       = document.getElementById("experience");

  form.addEventListener("submit", (e) => {
    const ok =
      chkName(nameEl) &&
      chkEmail(emailEl) &&
      chkStartDate(startEl) &&
      chkExperience(expEl);

    if (!ok) e.preventDefault();
  });
});

function chkName(name) {
    var myName = name;
    var pos = myName.value.search(/^[A-Za-z]+ [A-Za-z]+$/);

    if (pos != 0) {
        alert("Please enter a valid name. Only alphabetical characters are allowed.");
        myName.focus();
        myName.select();
        return false;
    }
    return true;
}

function chkEmail(email) {
    var ok = email.value.search(/^[\w.-]+@(?:\w+\.){1,3}[A-Za-z]{2,3}$/i);
    if (ok != 0) {
        alert("Please enter a valid email address.");
        email.focus();
        email.select();
        return false;
    }
    return true;
}

function chkStartDate(startDate) {
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    var inputDate = new Date(startDate.value);
    inputDate.setHours(0, 0, 0, 0);
    var sixMonthsFromNow = new Date();
    sixMonthsFromNow.setMonth(sixMonthsFromNow.getMonth() + 6);

    if (!startDate.value) {
        alert("Please enter a start date.");
        startDate.focus();
        startDate.select();
        return false;
    } else if (inputDate <= today) {
        alert("Start date cannot be today or in the past.");
        startDate.focus();
        startDate.select();
        return false;
    } else if (inputDate > sixMonthsFromNow) { //EXTRA: Future date limit check
        alert("Start date cannot be more than six months from today.");
        startDate.focus();
        startDate.select();
        return false;
    }
    return true;
}

function chkExperience(experience) {
    if (!experience.value.trim()) {
        alert("Please enter your experience.");
        experience.focus();
        experience.select();
        return false;
    } else if (experience.value.trim().length < 30) { // EXTRA: Minimum length check
        alert("Experience must be at least 30 characters long.");
        experience.focus();
        experience.select();
        return false;
    }
    return true;
}