<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");

$showtime_id = $_GET['id'] ?? '';
$conn->query("DELETE FROM showtimes WHERE id=$showtime_id");

header("Location: add_showtime.php");
exit();
?>
