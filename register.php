<?php
session_start();
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $jabatan = $_POST['jabatan'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize error message array
    $error_messages = [];

    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        $error_messages[] = "Username already exists";
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if($stmt->get_result()->num_rows > 0) {
        $error_messages[] = "Email already registered";
    }

    // Validate password match
    if($password !== $confirm_password) {
        $error_messages[] = "Passwords do not match";
    }

    // If there are errors, show alert and redirect
    if(!empty($error_messages)) {
        $error_string = implode("\\n", $error_messages); // Join errors with newline for alert
        echo "<script>
            alert('{$error_string}');
            window.location.href = 'register_page.php';
        </script>";
        exit();
    }

    // If no errors, proceed with registration
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, jabatan, username, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $jabatan, $username, $hashed_password);

    if($stmt->execute()) {
        // Registration successful
        echo "<script>
            alert('Registration successful! Please login.');
            window.location.href = 'index.php';
        </script>";
        exit();
    } else {
        // Registration failed
        echo "<script>
            alert('Registration failed. Please try again.');
            window.location.href = 'register_page.php';
        </script>";
        exit();
    }
}

// If not POST request, redirect to registration page
header("Location: register_page.php");
exit();
?>