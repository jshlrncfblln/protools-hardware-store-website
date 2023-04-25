function showHidePassword() {
    var passwordField = document.getElementById("password");
    var icon = document.querySelector(".show-hide");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      icon.style.background = "url('eye-off.svg') no-repeat center center";
    } else {
      passwordField.type = "password";
      icon.style.background = "url('eye.svg') no-repeat center center";
    }
  }
  