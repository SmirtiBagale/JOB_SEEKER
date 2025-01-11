function validateField(field, regex, errorMsg) {
  const errorElement = document.getElementById(`${field.id}_error`);
  if (!regex.test(field.value)) {
    errorElement.textContent = errorMsg;
    return false;
  } else {
    errorElement.textContent = "";
    return true;
  }
}

function validateForm() {
  const username = document.getElementById("username");
  const password = document.getElementById("password");

  const isUsernameValid = validateField(
    username,
    /\S+/,
    "Username is required."
  );
  const isPasswordValid = validateField(
    password,
    /\S+/,
    "Password is required."
  );

  return isUsernameValid && isPasswordValid;
}

function addRealTimeValidation() {
  document.getElementById("username").addEventListener("input", (e) => {
    validateField(e.target, /\S+/, "Username is required.");
  });
  document.getElementById("password").addEventListener("input", (e) => {
    validateField(e.target, /\S+/, "Password is required.");
  });
}

window.onload = addRealTimeValidation;
