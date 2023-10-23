<?php
session_start();

// Unset all session values
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: index.php');
exit;
?>
