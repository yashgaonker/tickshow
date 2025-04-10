<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");

$theater_id = $_GET['id'] ?? '';
$conn->query("DELETE FROM theaters WHERE id=$theater_id");

header("Location: admin_dashboard.php");
exit();
?>
