<?php
include 'connection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Register'])) {
    header('Content-Type: application/json');
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = $_POST['pass'];
    $confirmpassword = $_POST['confirmpassword'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contactno = trim($_POST['contact_no']);
    $emailid = trim($_POST['email_id']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincod']);
    $state = trim($_POST['state']);
    if ($password !== $confirmpassword) {
        http_response_code(400);
        echo json_encode(['message' => 'Passwords do not match.']);
        exit;
    }
    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(['message' => 'Password must be at least 8 characters long.']);
        exit;
    }
    $plain_password = password_hash($password, PASSWORD_DEFAULT);
    $sql_check = "SELECT user_username, email_id, contact_no FROM `users` WHERE user_username = ? OR email_id = ? OR contact_no = ?";
    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check === false) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $conn->error]);
        $conn->close();
        exit;
    }
    $stmt_check->bind_param("sss", $username, $emailid, $contactno);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['message' => 'A user with this username, email, or contact number already exists.']);
        $stmt_check->close();
        $conn->close();
        exit;
    }
    $stmt_check->close();
    $sql_insert = "INSERT INTO `users` (`first_name`, `last_name`, `user_username`, `user_password`, `dob`, `gender`, `address`, `city`, `contact_no`, `email_id`, `reg_date`, `state`, `pincode`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    if ($stmt_insert === false) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $conn->error]);
        $conn->close();
        exit;
    }
    $stmt_insert->bind_param("ssssssssssss", $firstname, $lastname, $username, $plain_password, $dob, $gender, $address, $city, $contactno, $emailid, $state, $pincode);
    if ($stmt_insert->execute()) {
        echo json_encode(['message' => 'Registration Successful !!']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Error: ' . $stmt_insert->error]);
    }
    $stmt_insert->close();
    $conn->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Aashray - Sign Up</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="register-page">
    <div id="custom-alert-box"></div>
    <div id="custom-success-box"></div>
    <div class="main-container">
        <div class="form-container">
            <div class="icon">
                <img src="assets/img/favicon.png" alt="Logo">
            </div>
            <form id="registrationForm" autocomplete="off" novalidate>
                <div class="title"></div>
                <div class="msg" id="form-msg"></div>
                <div class="form-group half-width">
                    <div class="input-wrapper">
                        <label for="firstname">First Name :</label>
                        <input type="text" placeholder="First Name" name="firstname" id="firstname" onkeypress="validateName(event)" oninput="formatInput()" required>
                        <div class="error-msg" id="firstname-error">First Name is required.</div>
                    </div>
                    <div class="input-wrapper">
                        <label for="lastname">Last Name :</label>
                        <input type="text" placeholder="Last Name" name="lastname" id="lastname" onkeypress="validateName(event)" oninput="formatinput()" required>
                        <div class="error-msg" id="lastname-error">Last Name is required.</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Username :</label>
                    <input type="text" placeholder="Username" name="username" id="username" onkeypress="validateuserame(event)" oninput="checkUsernameAvailability(this.value)" required>
                    <div class="error-msg" id="username-error">Username must be between 5-20 characters and contain only lowercase letters and numbers.</div>
                </div>
                <div class="form-group half-width">
                    <div class="input-wrapper">
                        <label for="pass">Password :</label>
                        <div class="pass-container">
                            <input type="password" placeholder="Password" name="pass" id="pass" onkeypress="restrictPassword(event)" onblur="passwordsize(event)" required="Paasword Required">
                            <span id="togglePassBtn" type="button" onclick="togglePass()">Show</span>
                        </div>
                        <div class="error-msg" id="pass-error">Password must be at least 8 characters long.</div>
                    </div>
                    <div class="input-wrapper">
                        <label for="confirmpassword">Confirm Password :</label>
                        <div class="pass-container">
                            <input type="password" placeholder="Confirm Password" name="confirmpassword" id="confirmpassword" onkeypress="restrictPassword(event)" required>
                            <span id="toggleConfPassBtn" type="button" onclick="toggleConfirmPass()">Show</span>
                        </div>
                        <div class="error-msg" id="confirmpassword-error">Passwords do not match.</div>
                    </div>
                </div>
                <div class="form-group half-width">
                    <div class="input-wrapper">
                        <label for="dob">Date of Birth :</label>
                        <input type="date" placeholder="Date of Birth" name="dob" id="dob" required>
                        <div class="error-msg" id="dob-error">Date of birth is required.</div>
                    </div>
                    <div class="input-wrapper">
                        <div class="form-group gender-dropdown">
                            <label for="gender">Gender :</label>
                            <select id="gender" name="gender" required>
                                <option value="">- Select Gender -</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="error-msg" id="gender-error">Gender is required.</div>
                        </div>
                    </div>
                </div>
                <div class="form-group half-width">
                    <div class="input-wrapper">
                        <label for="contact_no">Contact Number :</label>
                        <input type="text" placeholder="Contact No." name="contact_no" id="contact_no" oninput="restrictInput(event); checkContactAvailability(this.value);" onblur="validateMobileNumber(event)"
                            onkeypress="restrictToNumbers(event)" required>
                        <div class="error-msg" id="contact_no-error">Contact number must be 10 digits long.</div>
                    </div>
                    <div class="input-wrapper">
                        <label for="email_id">E-mail Id :</label>
                        <input type="email" placeholder="E-mail Id" name="email_id" id="email_id" oninput="checkEmailAvailability(this.value)" required>
                        <div class="error-msg" id="email_id-error">A valid email address is required.</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Permanent Address / Postal Address :</label>
                    <textarea rows="4" placeholder="Address" name="address" id="address" required></textarea>
                    <div class="error-msg" id="address-error">Address is required.</div>
                </div>
                <div class="form-group third-width">
                    <div class="input-wrapper">
                        <label for="city">City :</label>
                        <input type="text" placeholder="City" name="city" id="city" onkeypress="validateCity(event)" oninput="formatcity()" required>
                        <div class="error-msg" id="city-error">City is required.</div>
                    </div>
                    <div class="input-wrapper">
                        <label for="pincod">Pincode :</label>
                        <input type="text" placeholder="Pincode" name="pincod" id="pincod" oninput="restrictInputPin(event)" onblur="validatePincode(event)" onkeypress="restrictToPin(event)" required>
                        <div class="error-msg" id="pincod-error">Pincode must be 6 digits long.</div>
                    </div>
                    <div class="input-wrapper">
                        <label for="state">State :</label>
                        <input type="text" placeholder="State" name="state" id="state" onkeypress="validateCity(event)" oninput="formatCity()" required>
                        <div class="error-msg" id="state-error">State is required.</div>
                    </div>
                </div>
                <div class="btn-container">
                    <button type="submit" id="register-btn" name="Register">Register</button>
                </div>
                <div class="signup">Already have an Account? <a href="index.php">Login</a></div>
            </form>
        </div>
    </div>
    <script>
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        function validateName(event) {
            var input = event.key;
            if (!/[a-zA-Z]/.test(input)) {
                event.preventDefault();
            }
            var inputlength = event.target;
            if (inputlength.value.length >= 10) {
                inputlength.value = inputlength.value.slice(0, 10);
                event.preventDefault();
            }
        }

        function formatInput() {
            var inputField = document.getElementById("firstname");
            var inputValue = inputField.value;
            var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
            inputField.value = formattedValue;
        }

        function formatinput() {
            var inputField = document.getElementById("lastname");
            var inputValue = inputField.value;
            var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
            inputField.value = formattedValue;
        }

        function showAlert(message) {
            const alertBox = document.getElementById('custom-alert-box');
            alertBox.textContent = message;
            alertBox.style.display = 'block';
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 3000);
        }

        function validateuserame(event) {
            var input = event.key;
            if (!/[a-z0-9]/.test(input)) {
                event.preventDefault();
            }
            var inputlength = event.target;
            if (inputlength.value.length >= 20) {
                inputlength.value = inputlength.value.slice(0, 20);
                event.preventDefault();
            }
            if (inputlength.value.length === 0 && !/[a-z]/.test(input)) {
                event.preventDefault();
                return;
            }
        }

        function restrictPassword(event) {
            var input = event.target;
            var inputValue = input.value.trim();
            var pattern = /^[A-Za-z(){}!@#$%^&*]*$/;
            if (!pattern.test(inputValue)) {
                input.value = inputValue.replace(/[^A-Za-z\d(){}!@#$%^&*]/g, '');
            }
            var inputlength = event.target;
            if (inputlength.value.length >= 25) {
                inputlength.value = inputlength.value.slice(0, 25);
                event.preventDefault();
            }
        }

        function passwordsize(event) {
            var input = event.target;
            var inputvalue = input.value.trim();
            if (inputvalue.length < 8) {
                showError(currentField, "Password must be at least 8 characters long!");
                event.preventDefault();
            }
        }

        function showSuccessAlert(message) {
            const successBox = document.getElementById('custom-success-box');
            successBox.textContent = message;
            successBox.style.display = 'block';
            setTimeout(() => {
                successBox.style.display = 'none';
            }, 3000);
        }

        function togglePass() {
            var passwordInput = document.getElementById("pass");
            var toggleButton = document.getElementById("togglePassBtn");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleButton.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                toggleButton.textContent = "Show";
            }
        }

        function toggleConfirmPass() {
            var passwordInput = document.getElementById("confirmpassword");
            var toggleButton = document.getElementById("toggleConfPassBtn");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleButton.textContent = "Hide";
            } else {
                passwordInput.type = "password";
                toggleButton.textContent = "Show";
            }
        }
        window.onload = function() {
            var today = new Date();
            var maxDate = new Date();
            maxDate.setFullYear(today.getFullYear() - 15);
            var formattedMaxDate = maxDate.toISOString().split('T')[0];
            document.querySelector('#dob').max = formattedMaxDate;
            var minDate = new Date(1961, 1, 1).toISOString().split('T')[0];
            document.querySelector('#dob').min = minDate;
        };

        function restrictToNumbers(event) {
            var key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        }

        function validateMobileNumber(event) {
            var mobileNumber = event.target.value.trim();
            if (mobileNumber.length !== 10 || isNaN(mobileNumber)) {
                showError(currentField, "Mobile Number must contain 10 digits!!");
                event.target.value = "";
            }
        }

        function restrictInput(event) {
            if (event.target.value.length >= 10) {
                event.target.value = event.target.value.slice(0, 10);
                event.preventDefault();
            }
        }

        function validateCity(event) {
            var input = event.key;
            if (!/[a-zA-Z]/.test(input)) {
                event.preventDefault();
            }
            var inputlength = event.target;
            if (inputlength.value.length >= 50) {
                inputlength.value = inputlength.value.slice(0, 50);
                event.preventDefault();
            }
        }

        function formatcity() {
            var inputField = document.getElementById("city");
            var inputValue = inputField.value;
            var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
            inputField.value = formattedValue;
        }

        function formatCity() {
            var inputField = document.getElementById("state");
            var inputValue = inputField.value;
            var formattedValue = inputValue.charAt(0).toUpperCase() + inputValue.slice(1).toLowerCase();
            inputField.value = formattedValue;
        }

        function restrictToPin(event) {
            var key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        }

        function validatePincode(event) {
            var mobileNumber = event.target.value.trim();
            if (mobileNumber.length !== 6 || isNaN(mobileNumber)) {
                showError(currentField, "Pincode must contain 6  digits!!");
                event.target.value = "";
            }
        }

        function restrictInputPin(event) {
            if (event.target.value.length >= 6) {
                event.target.value = event.target.value.slice(0, 6);
                event.preventDefault();
            }
        }

        function setError(fieldId, message) {
            const errorElement = document.getElementById(`${fieldId}-error`);
            if (errorElement) {
                if (message) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'block';
                } else {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
            }
        }

        function validateField(fieldId) {
            const field = document.getElementById(fieldId);
            if (!field) return true;
            const value = field.value.trim();
            let errorMessage = '';
            switch (fieldId) {
                case 'firstname':
                case 'lastname':
                case 'address':
                case 'city':
                case 'state':
                    if (value.length === 0) errorMessage = 'This field is required.';
                    break;
                case 'username':
                    if (
                        value.length < 5 ||
                        value.length > 20 ||
                        !/^[a-z0-9]+$/.test(value) ||
                        !/\d$/.test(value)
                    ) {
                        errorMessage = 'Username must be 5-20 characters, lowercase alphanumeric, and end with a number.';
                    }
                    break;
                case 'pass':
                    if (value.length < 8) errorMessage = 'Password must be at least 8 characters long.';
                    break;
                case 'confirmpassword':
                    const password = document.getElementById('pass').value.trim();
                    if (value !== password) errorMessage = 'Passwords do not match.';
                    break;
                case 'dob':
                    if (value.length === 0) errorMessage = 'Date of birth is required.';
                    break;
                case 'gender':
                    if (value === '') errorMessage = 'Gender is required.';
                    break;
                case 'contact_no':
                    if (value.length !== 10 || isNaN(value)) errorMessage = 'Contact number must be 10 digits.';
                    break;
                case 'email_id':
                    if (!value.endsWith('@gmail.com')) {
                        errorMessage = 'A valid email address is required.';
                    } else if (!/^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(value)) {
                        errorMessage = 'Email format is invalid.';
                    }
                    break;
                case 'pincod':
                    if (value.length !== 6 || isNaN(value)) errorMessage = 'Pincode must be 6 digits.';
                    break;
            }
            setError(fieldId, errorMessage);
            return errorMessage === '';
        }

        function validateForm() {
            let isValid = true;
            const requiredFields = document.querySelectorAll('#registrationForm [required]');
            requiredFields.forEach(field => {
                if (!validateField(field.id)) {
                    isValid = false;
                }
            });
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email_id').value.trim();
            const contact = document.getElementById('contact_no').value.trim();
            if (username === lastTakenUsername) {
                setError('username', 'This username is already taken.');
                isValid = false;
            }
            if (email === lastTakenEmail) {
                setError('email_id', 'This email is already registered.');
                isValid = false;
            }
            if (contact === lastTakenContact) {
                setError('contact_no', 'This contact number is already registered.');
                isValid = false;
            }
            const formMsg = document.getElementById('form-msg');
            if (isValid) {
                formMsg.textContent = 'Great! All fields are valid.';
                formMsg.className = 'msg success';
                formMsg.style.display = 'block';
            } else {
                formMsg.textContent = 'Please correct the errors to proceed.';
                formMsg.className = 'msg error';
                formMsg.style.display = 'block';
            }
            return isValid;
        }

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }
        let lastTakenUsername = '';
        const checkUsernameAvailability = debounce(async (username) => {
            if (username.length < 5 || !/^[a-z0-9]+$/.test(username)) {
                return;
            }
            if (username === lastTakenUsername) {
                setError('username', 'This username is already taken.');
                return;
            }
            try {
                const response = await fetch('check_username.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `username=${encodeURIComponent(username)}`
                });
                const data = await response.json();
                if (data.username_exists) {
                    lastTakenUsername = username;
                    setError('username', 'This username is already taken.');
                } else {
                    lastTakenUsername = '';
                    setError('username', '');
                }
            } catch (error) {
                console.error('Error checking username:', error);
                setError('username', 'Could not verify username. Please try again.');
            }
        }, 500);
        let lastTakenEmail = '';
        let lastTakenContact = '';
        const checkEmailAvailability = debounce(async (email) => {
            if (!document.getElementById('email_id').validity.valid) {
                return;
            }
            if (email === lastTakenEmail) {
                setError('email_id', 'This email is already registered.');
                return;
            }
            try {
                const response = await fetch('check_email.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `email=${encodeURIComponent(email)}`
                });
                const data = await response.json();
                if (data.email_exists) {
                    lastTakenEmail = email;
                    setError('email_id', 'This email is already registered.');
                } else {
                    lastTakenEmail = '';
                    setError('email_id', '');
                }
            } catch (error) {
                console.error('Error checking email:', error);
                setError('email_id', 'Could not verify email. Please try again.');
            }
        }, 500);
        const checkContactAvailability = debounce(async (contact_no) => {
            if (contact_no.length !== 10 || isNaN(contact_no)) {
                return;
            }
            if (contact_no === lastTakenContact) {
                setError('contact_no', 'This contact number is already registered.');
                return;
            }
            try {
                const response = await fetch('check_contact_no.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `contact_no=${encodeURIComponent(contact_no)}`
                });
                const data = await response.json();
                if (data.contact_no_exists) {
                    lastTakenContact = contact_no;
                    setError('contact_no', 'This contact number is already registered.');
                } else {
                    lastTakenContact = '';
                    setError('contact_no', '');
                }
            } catch (error) {
                console.error('Error checking contact number:', error);
                setError('contact_no', 'Could not verify contact number. Please try again.');
            }
        }, 500);
        document.addEventListener('DOMContentLoaded', function() {
            const registrationForm = document.getElementById('registrationForm');
            const registerBtn = document.getElementById('register-btn');
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email_id');
            const contactInput = document.getElementById('contact_no');
            var today = new Date();
            var maxDate = new Date();
            maxDate.setFullYear(today.getFullYear() - 15);
            var formattedMaxDate = maxDate.toISOString().split('T')[0];
            document.querySelector('#dob').max = formattedMaxDate;
            var minDate = new Date(1947, 0, 1).toISOString().split('T')[0];
            document.querySelector('#dob').min = minDate;
            registrationForm.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('input', () => {
                    validateField(field.id);
                    validateForm();
                });
                field.addEventListener('change', () => {
                    validateField(field.id);
                    validateForm();
                });
            });
            usernameInput.addEventListener('keyup', (event) => {
                const username = event.target.value.trim();
                if (username.length > 0) {
                    checkUsernameAvailability(username);
                } else {
                    setError('username', '');
                }
            });
            emailInput.addEventListener('keyup', (event) => {
                const email = event.target.value.trim();
                if (email.length > 0) {
                    checkEmailAvailability(email);
                } else {
                    setError('email_id', '');
                }
            });
            contactInput.addEventListener('keyup', (event) => {
                const contact_no = event.target.value.trim();
                if (contact_no.length > 0) {
                    checkContactAvailability(contact_no);
                } else {
                    setError('contact_no', '');
                }
            });
            validateForm();
            registrationForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                if (validateForm()) {
                    const formData = new FormData(registrationForm);
                    formData.append('Register', 'true');
                    try {
                        const response = await fetch('register.php', {
                            method: 'POST',
                            body: formData
                        });
                        const result = await response.json();
                        if (response.ok) {
                            showSuccessAlert(result.message);
                            console.log("Form data submitted successfully!");
                            registrationForm.reset();
                            registerBtn.classList.remove('shift-left', 'shift-right');
                            validateForm();
                            setTimeout(() => {
                                window.location.href = "index.php";
                            }, 2000);
                        } else {
                            showAlert(result.message);
                        }
                    } catch (error) {
                        console.error('Error submitting form:', error);
                        showAlert("An error occurred during registration. Please try again.");
                    }
                } else {
                    showAlert("Please correct the form errors.");
                }
            });
            registerBtn.addEventListener('mouseover', function() {
                if (!validateForm()) {
                    const currentPosition = registerBtn.classList.contains('shift-left') ? 'shift-left' : 'shift-right';
                    const nextPosition = currentPosition === 'shift-left' ? 'shift-right' : 'shift-left';
                    registerBtn.classList.remove(currentPosition);
                    registerBtn.classList.add(nextPosition);
                } else {
                    registerBtn.classList.remove('shift-left', 'shift-right');
                }
            });
        });

        function showError(fieldId, message) {
            var field = document.getElementById(fieldId);
            if (field) {
                var errorSpan = field.nextElementSibling;
                if (errorSpan && errorSpan.classList.contains("error-message")) {
                    errorSpan.textContent = message;
                }
            }
        }

        function clearError(fieldId) {
            var field = document.getElementById(fieldId);
            if (field) {
                var errorSpan = field.nextElementSibling;
                if (errorSpan && errorSpan.classList.contains("error-message")) {
                    errorSpan.textContent = "";
                }
            }
        }
    </script>
</body>

</html>