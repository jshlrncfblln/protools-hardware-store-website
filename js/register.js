//getting the id for validation
const form = document.getElementById("registrationForm");
const fnameInput = document.getElementById("fname");
const snameInput = document.getElementById("sname");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm-password");

//getting the id for error message
const fnameError = document.getElementById("fnameError");
const snameError = document.getElementById("snameError");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
const confirmPasswordError = document.getElementById("confirmPasswordError");

form.addEventListener("submit", function(event){
  event.preventDefault();
  validateForm();
});

fnameInput.addEventListener("input", validateFirstName);
snameInput.addEventListener("input", validateSurName);
emailInput.addEventListener("input", validateEmail);
passwordInput.addEventListener("input", validatePassword);
confirmPasswordInput.addEventListener("input", validateConfirmPassword);

function validateForm(){
  const fnameValid = validateFirstName();
  const snameValid = validateSurName();
  const emailValid = validateEmail();
  const passValid = validatePassword();
  const cpassValid = validateConfirmPassword();

  if (fnameValid && snameValid && emailValid && passValid && cpassValid){
    form.submit();
  }
}

function validateFirstName() {
  const firstNameValue = fnameInput.value.trim();
  const regex = /^[A-Za-z]+$/;

  if (firstNameValue === "") {
    fnameInput.classList.remove("error");
    fnameError.textContent = "";
    return true;
  } else if (!regex.test(firstNameValue)) {
    fnameInput.classList.add("error");
    fnameError.textContent = "First name must consist only of letters";
    return false;
  } else {
    fnameInput.classList.remove("error");
    fnameError.textContent = "";
    return true;
  }
}

function validateSurname() {
  const surnameValue = snameInput.value.trim();
  const regex = /^[A-Za-z]+$/;

  if (surnameValue === "") {
    snameInput.classList.remove("error");
    snameError.textContent = "";
    return true;
  } else if (!regex.test(surnameValue)) {
    snameInput.classList.add("error");
    snameError.textContent = "Surname must consist only of letters";
    return false;
  } else {
    snameInput.classList.remove("error");
    snameError.textContent = "";
    return true;
  }
}

function validateEmail() {
  const emailValue = emailInput.value.trim();
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (emailValue === "") {
    emailInput.classList.remove("error");
    emailError.textContent = "";
    return true;
  } else if (!regex.test(emailValue)) {
    emailInput.classList.add("error");
    emailError.textContent = "Invalid email format";
    return false;
  } else {
    emailInput.classList.remove("error");
    emailError.textContent = "";
    return true;
  }
}

function validatePassword() {
  const passwordValue = passwordInput.value;
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

  if (passwordValue === "") {
    passwordInput.classList.remove("error");
    passwordError.textContent = "";
    return true;
  } else if (!regex.test(passwordValue)) {
    passwordInput.classList.add("error");
    passwordError.textContent = "Password must be 8 characters long and contain at least 1 uppercase letter, 1 lowercase letter, and 1 number";
    return false;
  } else {
    passwordInput.classList.remove("error");
    passwordError.textContent = "";
    return true;
  }
}

function validateConfirmPassword() {
  const confirmPasswordValue = confirmPasswordInput.value;
  const passwordValue = passwordInput.value;

  if (confirmPasswordValue === "") {
    confirmPasswordInput.classList.remove("error");
    confirmPasswordError.textContent = "";
    return true;
  } else if (confirmPasswordValue !== passwordValue) {
    confirmPasswordInput.classList.add("error");
    confirmPasswordError.textContent = "Passwords do not match";
    return false;
  } else {
    confirmPasswordInput.classList.remove("error");
    confirmPasswordError.textContent = "";
    return true;
  }
}
