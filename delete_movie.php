<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");

$movie_id = $_GET['id'] ?? '';
$conn->query("DELETE FROM movies WHERE id=$movie_id");

header("Location: add_movies.php");
exit();
?>
