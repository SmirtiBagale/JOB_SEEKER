function validateField(field, regex, errorMsg) {
  const errorElement = document.getElementById(`${field.id}_error`);
  if (!regex.test(field.value.trim())) {
    errorElement.textContent = errorMsg;
    return false;
  } else {
    errorElement.textContent = "";
    return true;
  }
}

function validateForm() {
  const fullName = document.getElementById("full_name");
  const email = document.getElementById("email");
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm_password");

  const isFullNameValid = validateField(
    fullName,
    /^[a-zA-Z\s]+$/,
    "Full Name is required and should only contain letters."
  );
  const isEmailValid = validateField(
    email,
    /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    "Please enter a valid email address."
  );
  const isUsernameValid = validateField(
    username,
    /^[a-zA-Z0-9_]+$/,
    "Username is required and should only contain alphanumeric characters."
  );
  const isPasswordValid = validateField(
    password,
    /.{6,}/,
    "Password must be at least 6 characters long."
  );
  const isConfirmPasswordValid = password.value === confirmPassword.value;

  if (!isConfirmPasswordValid) {
    document.getElementById("confirm_password_error").textContent =
      "Passwords do not match.";
  } else {
    document.getElementById("confirm_password_error").textContent = "";
  }

  return (
    isFullNameValid &&
    isEmailValid &&
    isUsernameValid &&
    isPasswordValid &&
    isConfirmPasswordValid
  );
}

function addRealTimeValidation() {
  document.getElementById("full_name").addEventListener("input", (e) => {
    validateField(
      e.target,
      /^[a-zA-Z\s]+$/,
      "Full Name is required and should only contain letters."
    );
  });
  document.getElementById("email").addEventListener("input", (e) => {
    validateField(
      e.target,
      /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
      "Please enter a valid email address."
    );
  });
  document.getElementById("username").addEventListener("input", (e) => {
    validateField(
      e.target,
      /^[a-zA-Z0-9_]+$/,
      "Username is required and should only contain alphanumeric characters."
    );
  });
  document.getElementById("password").addEventListener("input", (e) => {
    validateField(
      e.target,
      /.{6,}/,
      "Password must be at least 6 characters long."
    );
  });
  document.getElementById("confirm_password").addEventListener("input", (e) => {
    const password = document.getElementById("password");
    const confirmPassword = e.target;
    const errorElement = document.getElementById("confirm_password_error");
    if (password.value !== confirmPassword.value) {
      errorElement.textContent = "Passwords do not match.";
    } else {
      errorElement.textContent = "";
    }
  });
}

window.onload = addRealTimeValidation;
