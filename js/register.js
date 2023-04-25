function showHidePassword(id, parent) {
  var passwordField = document.getElementById(id);
  var showHideIcon = parent.querySelector('.show-hide');
  if (passwordField.type === "password") {
    passwordField.type = "text";
    showHideIcon.classList.remove("fa-eye-slash");
    showHideIcon.classList.add("fa-eye");
    parent.classList.add("active");
  } else {
    passwordField.type = "password";
    showHideIcon.classList.remove("fa-eye");
    showHideIcon.classList.add("fa-eye-slash");
    parent.classList.remove("active");
  }
}
