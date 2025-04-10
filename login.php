<?php
session_start(); // Start the session

include("db.php"); // Your DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Prepare the query
    $sql = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // If user found
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"]; // Set session
                $_SESSION["email"] = $user["email"];

                header("Location: index.php"); // Redirect after login
                exit();
            } else {
                echo "<script>alert('Incorrect password.'); window.location.href='login.html';</script>";
            }
        } else {
            echo "<script>alert('No account found with this email.'); window.location.href='register.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Database error. Please try again later.'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='login.html';</script>";
}
?>
