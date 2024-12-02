<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <title>REGISTER</title>
<head>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .register-container {
            background-color: #E6E6FA;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        .register-title {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
            font-size: 0.9rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0056b3;
            box-shadow: 0 0 0 2px rgba(0,86,179,0.1);
        }

        .form-group select {
            background-color: white;
            cursor: pointer;
        }

        .register-button {
            width: 100%;
            padding: 0.8rem;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-top: 1.5rem;
        }

        .register-button:hover {
            background-color: #0056b3;
        }

        .register-button:active {
            transform: translateY(1px);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            padding: 0.5rem;
            background-color: rgba(220, 53, 69, 0.1);
            border-radius: 4px;
            display: none;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .login-link a {
            color: #0066cc;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* System messages */
        .system-message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            text-align: center;
            font-size: 0.9rem;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-alert {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive adjustments */
        @media (max-width: 400px) {
            .register-container {
                width: 90%;
                padding: 1.5rem;
            }

            .form-group input,
            .form-group select,
            .register-button {
                padding: 0.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-title">REGISTER</div>
        <?php if(!empty($errors)): ?>
            <div class="system-message error-alert">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="register.php" id="registerForm">
            <div class="form-group">
                <label for="fullname">Full Name:</label>
                <input 
                    type="text" 
                    id="fullname" 
                    name="fullname" 
                    required
                    placeholder="Enter your full name"
                >
                <div class="error-message" id="fullnameError">Please enter your full name</div>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    placeholder="Enter your email address"
                >
                <div class="error-message" id="emailError">Please enter a valid email address</div>
            </div>

            <div class="form-group">
                <label for="jabatan">Department:</label>
                <select id="jabatan" name="jabatan" required>
                    <option value="">Select Department</option>
                    <option value="pentadbiran">Pentadbiran</option>
                    <option value="pembangunan">Pembangunan</option>
                    <option value="agensi_luar">Agensi Luar</option>
                </select>
                <div class="error-message" id="jabatanError">Please select your department</div>
            </div>

            <div class="form-group">
                <label for="username">Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
                    placeholder="Choose a username"
                >
                <div class="error-message" id="usernameError">Please enter a username</div>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="Enter your password"
                >
                <div class="error-message" id="passwordError">Password must be at least 6 characters</div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    required
                    placeholder="Confirm your password"
                >
                <div class="error-message" id="confirmPasswordError">Passwords do not match</div>
            </div>

            <button type="submit" class="register-button">REGISTER</button>

            <div class="login-link">
                Already have an account? <a href="index.php">Login here</a>
            </div>
        </form>
    </div>

    <script>
        function handleSubmit(event) {
            event.preventDefault();
            
            const fullname = document.getElementById('fullname');
            const email = document.getElementById('email');
            const jabatan = document.getElementById('jabatan');
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            // Reset error messages
            document.querySelectorAll('.error-message').forEach(msg => msg.style.display = 'none');
            
            let isValid = true;
            
            // Fullname validation
            if (!fullname.value.trim()) {
                document.getElementById('fullnameError').style.display = 'block';
                isValid = false;
            }
            
            // Email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailPattern.test(email.value)) {
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            }
            
            // Department validation
            if (!jabatan.value) {
                document.getElementById('jabatanError').style.display = 'block';
                isValid = false;
            }
            
            // Username validation
            if (!username.value.trim()) {
                document.getElementById('usernameError').style.display = 'block';
                isValid = false;
            }
            
            // Password validation
            if (!password.value || password.value.length < 6) {
                document.getElementById('passwordError').style.display = 'block';
                isValid = false;
            }
            
            // Confirm password validation
            if (password.value !== confirmPassword.value) {
                document.getElementById('confirmPasswordError').style.display = 'block';
                isValid = false;
            }
            
            if (isValid) {
                // Submit the form
                event.target.submit();
            }
        }

        // Add the event listener to the form
        document.getElementById('registerForm').addEventListener('submit', handleSubmit);
    </script>
</body>
</html>