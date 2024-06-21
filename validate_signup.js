function validateSignup() {
    // Get references to form fields
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
  
    // Clear any existing error messages
    clearErrorMessages();  // Call a function to clear error messages
  
    // Validation checks
    let isValid = true;
  
    // Check if username is empty or less than 3 characters
    if (username.value === "" || username.value.length < 3) {
      displayErrorMessage(username, "Username must be at least 3 characters long.");
      isValid = false;
    }
  
    // Check if email is empty or invalid format
    if (email.value === "" || !validateEmail(email.value)) {
      displayErrorMessage(email, "Invalid email format.");
      isValid = false;
    }
  
    // Check if password is empty or less than 8 characters
    if (password.value === "" || password.value.length < 8) {
      displayErrorMessage(password, "Password must be at least 8 characters long.");
      isValid = false;
    }
  
    // Check if passwords match
    if (password.value !== confirmPassword.value) {
      displayErrorMessage(password, "Passwords do not match.");
      displayErrorMessage(confirmPassword, "Passwords do not match."); // Duplicate for confirmation field
      isValid = false;
    }
  
    return isValid;  // Return true if validation passes, false otherwise
  }
  
function validateEmail(email) {
    const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return re.test(String(email).toLowerCase());
}

function clearErrorMessages() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(errorMessage => errorMessage.remove());
}

function displayErrorMessage(element, message) {
    const error = document.createElement('div');
    error.className = 'error-message';
    error.style.color = 'red';
    error.innerText = message;
    element.parentNode.insertBefore(error, element.nextSibling);
}
