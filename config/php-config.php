<?php
// PHP 5.6.40 Configuration for Windows Server 2012 R2 IIS 8.5

// Set the default timezone
date_default_timezone_set('UTC');

// Set the character encoding
header('Content-Type: text/html; charset=UTF-8');

// Error handling settings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Custom error handling function
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Handle the error accordingly
    echo "Error: [$errno] $errstr - $errfile:$errline";
});
?>