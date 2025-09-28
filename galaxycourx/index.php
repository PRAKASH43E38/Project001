

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Registration</title>
</head>
<body>
<div class="message-container" id="messageContainer"></div>

<div class="overlay">
    <div class="container">
        <div class="left">
            <h1>GALAXY COURSE PLATFORM</h1>
            
            
        </div>
        <div class="right">
            <h2>REGISTER HERE</h2>
            <form id="registrationForm" method="POST" action="admin.php">
                <div class="form-group">
                    <input type="text" name="fullName" placeholder="Enter Full Name" required />
                </div><br>
                <div class="form-group">
                    <input type="number" name="age" placeholder="Age" required />
                </div><br>
                <div class="form-group">
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div><br>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required />
                </div><br>
                <div class="form-group">
                    <input type="number" name="phoneNumber" placeholder="Phone Number" required />
                </div><br>
                <div class="form-group">
                    <select name="courseType" required>
                        <option value="">Course Type*</option>
                        <option value="web">Web Development</option>
                        <option value="ai">AI & ML</option>
                        <option value="genai">Generative AI</option>
                        <option value="data">Data Analytics</option>
                        <option value="blockchain">Blockchain</option>
                        <option value="cyber">Cyber Security</option>
                    </select>
                </div><br>
                <button type="submit" class="submit-btn" id="registerButton">
                    Register
                    <span class="loading-spinner" id="loadingSpinner"></span>
                </button>
                <br>
                <div class="submit-btn">
                    <br>
                    <a href="admin.php">View Dashboard</a>
                    <style>
                        .a{
                            width: 100%;
                            height: 100vh;
                            text-decoration: none;
                        }
                    </style>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const registrationForm = document.getElementById('registrationForm');
        const messageContainer = document.getElementById('messageContainer');
        const registerButton = document.getElementById('registerButton');
        const loadingSpinner = document.getElementById('loadingSpinner');

        // Function to display a message
        function displayMessage(text, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}-message`;
            messageDiv.textContent = text;
            messageContainer.innerHTML = ''; // Clear previous messages
            messageContainer.appendChild(messageDiv);

            // Show the message
            setTimeout(() => {
                messageDiv.classList.add('show');
            }, 100);

            // Hide the message after 3 seconds
            setTimeout(() => {
                messageDiv.classList.remove('show');
                messageDiv.addEventListener('transitionend', () => messageDiv.remove());
            }, 3000);
        }

        // Handle form submission
        registrationForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission (stops navigation)

            registerButton.disabled = true; // Disable button during submission
            loadingSpinner.style.display = 'inline-block'; // Show spinner

            const formData = new FormData(registrationForm);

            fetch('admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Check if the response is OK (status 200-299)
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); // Assume admin.php returns JSON
            })
            .then(data => {
                if (data.status === 'success') {
                    displayMessage('Registration successful!', 'success');
                    registrationForm.reset(); // Reset the form fields
                } else {
                    displayMessage('Registration failed: ' + (data.message || 'Unknown error.'), 'error');
                }
            })
            .catch(error => {
                console.error('Error during fetch:', error);
                displayMessage('Registration successful', 'error');
            })
            .finally(() => {
                registerButton.disabled = false; // Re-enable button
                loadingSpinner.style.display = 'none'; // Hide spinner
            });
        });

        // Original logic for displaying messages from URL parameters (if admin.php redirects back, though not expected with AJAX)
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        if (status) {
            let messageTextFromURL = '';
            let messageClassFromURL = '';
            if (status === 'success') {
                messageTextFromURL = 'Registration successful!';
                messageClassFromURL = 'success';
            } else if (status === 'error') {
                const errorMsg = urlParams.get('msg') || 'An unknown error occurred.';
                messageTextFromURL = 'Registration failed: ' + decodeURIComponent(errorMsg);
                messageClassFromURL = 'error';
            } else if (status === 'db_error') {
                messageTextFromURL = 'Database connection failed. Please try again later.';
                messageClassFromURL = 'error';
            } else if (status === 'sql_error') {
                messageTextFromURL = 'An internal server error occurred during registration.';
                messageClassFromURL = 'error';
            }
            if (messageTextFromURL) {
                displayMessage(messageTextFromURL, messageClassFromURL);
            }
            // Clear the URL parameters after displaying the message to prevent re-showing on refresh
            history.replaceState({}, document.title, window.location.pathname);
        }
    });
</script>
</body>
</html>