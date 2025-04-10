<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$movie_id = $_GET['id'] ?? '';
$movie = $conn->query("SELECT * FROM movies WHERE id=$movie_id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $duration = $_POST['duration'];
    $release_date = $_POST['release_date'];
    $showtime = $_POST['showtime'];

    // Handle file upload
    if (!empty($_FILES["poster"]["name"])) {
        $file_name = basename($_FILES["poster"]["name"]);
        $target_file = "uploads/" . $file_name;
        move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file);
        $conn->query("UPDATE movies SET poster='$file_name' WHERE id=$movie_id");
    }

    $query = "UPDATE movies SET title=?, genre=?, duration=?, release_date=?, showtime=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssissi", $title, $genre, $duration, $release_date, $showtime, $movie_id);
    
    if ($stmt->execute()) {
        header("Location: movies.php");
        exit();
    } else {
        echo "Error updating movie.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
</head>
<body>
    <h2>Edit Movie</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" value="<?= $movie['title'] ?>" required><br>
        <select name="genre" required>
            <option value="<?= $movie['genre'] ?>"><?= $movie['genre'] ?></option>
            <option value="Action">Action</option>
            <option value="Comedy">Comedy</option>
            <option value="Drama">Drama</option>
        </select><br>
        <input type="number" name="duration" value="<?= $movie['duration'] ?>" required><br>
        <input type="date" name="release_date" value="<?= $movie['release_date'] ?>" required><br>
        <input type="datetime-local" name="showtime" value="<?= $movie['showtime'] ?>" required><br>
        <input type="file" name="poster"><br>
        <button type="submit">Update Movie</button>
    </form>
</body>
</html>
