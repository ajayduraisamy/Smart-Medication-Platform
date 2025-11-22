<?php
session_start();
session_unset();   // Clear session variables
session_destroy(); // Destroy session

// Redirect to login page
header("Location: login");
exit();
?>
