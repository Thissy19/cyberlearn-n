function validateLoginForm() {
    // Get references to the username and password fields
    const username = document.getElementById('username');
    const password = document.getElementById('password');
  
    // Clear any existing error messages
    username.nextElementSibling.innerHTML = "";
    password.nextElementSibling.innerHTML = "";
  
    // Validation checks
    let isValid = true;
  
    // Check if username is empty
    if (username.value === "") {
      username.nextElementSibling.innerHTML = "Username cannot be empty";
      isValid = false;
    }
  
    // Check if password is empty
    if (password.value === "") {
      password.nextElementSibling.innerHTML = "Password cannot be empty";
      isValid = false;
    } else if (password.value.length < 8) {
      // Check password length
      password.nextElementSibling.innerHTML = "Password must be at least 8 characters long";
      isValid = false;
    }
  
    return isValid;
  }
  
  // Add event listener to the login form submission
  const loginForm = document.getElementById('login-form');
  loginForm.addEventListener('submit', function(event) {
    // Call the validation function
    if (!validateLoginForm()) {
      // Prevent form submission if validation fails
      event.preventDefault();
    }
  });