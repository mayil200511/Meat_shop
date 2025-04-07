<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();
// Redirect to main login page
header("Location:/meat_shop2/login.php");
exit;
?>