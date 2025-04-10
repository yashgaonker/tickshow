<?php
session_start(); // Start session for login purposes

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tickshow";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input fields
    if (empty($full_name) || empty($email) || empty($password)) {
        die("Error: All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        die("Error: Email is already registered.");
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profile_picture = $_FILES['profile_picture'];
        $target_dir = "profile_image/";
        $target_file = $target_dir . basename($profile_picture["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate image file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Move uploaded file to target directory
        if (!move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
            die("Error: Failed to upload profile picture.");
        }
    } else {
        $target_file = '<div class="">
        <images>3 idiots.jpeg'; // Set to empty if no picture is uploaded (handle this case)
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, profile_picture) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $target_file);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id; // Auto-login the user
        header("Location: index.php"); // Redirect to profile page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
$conn->close();
?>
