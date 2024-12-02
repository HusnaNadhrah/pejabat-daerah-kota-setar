<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session and include connection
session_start();
include('connection.php');

// Debug function
function debug_log($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'login_debug.log');
}

debug_log("Login process started");

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    debug_log("User already logged in - user_id: " . $_SESSION['user_id']);
    
    // If admin is logged in
    if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
        debug_log("Redirecting logged-in admin to admin dashboard");
        header("Location: admin_dashboard.php");
        exit();
    }
    // If regular user is logged in
    else {
        debug_log("Redirecting logged-in user to dashboard");
        header("Location: dashboard.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    debug_log("POST request received");
    
    // Validate POST data exists
    if(!isset($_POST['username']) || !isset($_POST['password'])) {
        debug_log("Missing username or password in POST data");
        header("Location: index.php");
        exit();
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    debug_log("Login attempt - username: " . $username);

    // Check for empty fields
    if(empty($username) || empty($password)) {
        debug_log("Empty username or password submitted");
        echo "<script>
            alert('Please fill in all fields!');
            window.location.href = 'index.php';
        </script>";
        exit();
    }

    // Check for admin credentials
    if($username == 'admin' && $password == 'admin') {
        debug_log("Admin login successful");
        
        // Set admin session variables
        $_SESSION['user_id'] = 'admin';
        $_SESSION['username'] = 'admin';
        $_SESSION['fullname'] = 'Administrator';
        $_SESSION['email'] = 'admin@admin.com';
        $_SESSION['is_admin'] = true;

        debug_log("Admin session created - redirecting to admin dashboard");
        header("Location: admin_dashboard.php");
        exit();
    }

    try {
        // Check regular user credentials
        debug_log("Checking regular user credentials");
        
        $stmt = $conn->prepare("SELECT id, fullname, email, username, password, jabatan FROM users WHERE username = ?");
        if(!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        if(!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        debug_log("Database query executed - found rows: " . $result->num_rows);

        if($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            debug_log("User found - verifying password");

            if(password_verify($password, $user['password'])) {
                debug_log("Password verified successfully");

                // Set user session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['jabatan'] = $user['jabatan'];
                $_SESSION['is_admin'] = false;

                debug_log("User session created - redirecting to dashboard");
                header("Location: dashboard.php");
                exit();
            } else {
                debug_log("Invalid password for user: " . $username);
                echo "<script>
                    alert('Invalid password!');
                    window.location.href = 'index.php';
                </script>";
                exit();
            }
        } else {
            debug_log("Username not found: " . $username);
            echo "<script>
                alert('Username not found!');
                window.location.href = 'index.php';
            </script>";
            exit();
        }
    } catch (Exception $e) {
        debug_log("Error in login process: " . $e->getMessage());
        echo "<script>
            alert('An error occurred during login. Please try again later.');
            window.location.href = 'index.php';
        </script>";
        exit();
    } finally {
        if(isset($stmt)) {
            $stmt->close();
        }
    }
}

// If accessed directly without POST
debug_log("Direct access without POST - redirecting to index");
header("Location: index.php");
exit();
?>