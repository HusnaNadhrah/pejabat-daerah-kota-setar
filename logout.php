<?php
session_start();

// Destroy all session data
session_destroy();

// Redirect to login page
echo "<script>
    alert('You have been logged out successfully!');
    window.location.href = 'index.php';
</script>";
exit();
?>