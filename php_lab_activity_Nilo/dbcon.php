<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "php_db";

// Connection
$conn = new mysqli($host, $username, $password, $dbname);
    // Check Connection
    if ($conn->connect_error) {
        die("Connection Error" . $conn->connect_error);
    }

?>
