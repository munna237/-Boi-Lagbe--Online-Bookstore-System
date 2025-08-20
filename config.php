<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = mysqli_connect('localhost','root','','shop_db') or die('connection failed: ' . mysqli_connect_error());
?>