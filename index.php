<?php
session_start();

?>
<!DOCTYPE html>
<html>
    <title>LOGIN</title>
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

        .login-container {
            background-color: #E6E6FA;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-title {
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
        }

        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0056b3;
        }

        .login-button {
            width: 100%;
            padding: 0.75rem;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">LOGIN</div>
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>
        <?php if(isset($error_message)): ?>
            <div class="error-message" style="display: block;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="login.php" id="loginForm">
            <div class="form-group">
                <label for="username">Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    required
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
                >
                <div class="error-message" id="passwordError">Please enter a password</div>
            </div>
            <button type="submit" class="login-button">LOGIN</button>
            <div class="register-link"><br>
                Don't have an account? <a style="text-decoration: none;color:blue;margin-top: 0.5rem;" href="register_page.php">Register here</a>
            </div>
        </form>
    </div>
</body>
</html>