<?php
include 'db.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    echo "<script>alert('Please login first!'); window.location.href='login.html';</script>";
    exit();
}

if (!isset($_POST['movie_id'], $_POST['selected_seats'], $_POST['total_price'])) {
    echo "<script>alert('Invalid booking details.'); window.location.href='index.php';</script>";
    exit();
}

$user_id = $_SESSION["user_id"];
$movie_id = intval($_POST['movie_id']);
$selected_seats = htmlspecialchars($_POST['selected_seats']);
$total_price = floatval($_POST['total_price']);
$booking_date = date("Y-m-d");
$created_at = date("Y-m-d H:i:s");
$status = "confirmed";

$sql = "INSERT INTO bookings (user_id, movie_id, selected_seats, total_price, booking_date, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssss", $user_id, $movie_id, $selected_seats, $total_price, $booking_date, $status, $created_at);

if ($stmt->execute()) {
    echo "<script>alert('seats reserved sucessfully!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='index.php';</script>";
}

$stmt->close();
$conn->close();
?>
