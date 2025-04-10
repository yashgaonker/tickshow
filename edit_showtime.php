<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");

$showtime_id = $_GET['id'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $show_time = $_POST['show_time'];

    $query = "UPDATE showtimes SET show_time=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $show_time, $showtime_id);

    if ($stmt->execute()) {
        echo "Showtime updated successfully!";
    } else {
        echo "Error updating showtime.";
    }
}

// Fetch existing data
$result = $conn->query("SELECT * FROM showtimes WHERE id=$showtime_id");
$showtime = $result->fetch_assoc();
?>

<form method="POST">
    <input type="time" name="show_time" value="<?= $showtime['show_time'] ?>" required>
    <button type="submit">Update Showtime</button>
</form>
