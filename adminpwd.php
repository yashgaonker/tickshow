<?php
$conn = new mysqli("localhost", "root", "", "tickshow");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Change 'yourpassword' to your desired admin password
$password = password_hash("yourpassword", PASSWORD_DEFAULT);
$username = "admin";  // Change this if needed

// Insert admin user into the database
$query = "INSERT INTO admins (username, password) VALUES ('$username', '$password')";
if ($conn->query($query) === TRUE) {
    echo "Admin user created successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
