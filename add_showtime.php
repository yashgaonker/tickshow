<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");

// Fetch theaters, movies, and showtimes
$theaters = $conn->query("SELECT * FROM theaters");
$movies = $conn->query("SELECT * FROM movies");
$showtimes = $conn->query("SELECT s.id, t.name as theater_name, m.title as movie_title, s.show_time 
                           FROM showtimes s
                           JOIN theaters t ON s.theater_id = t.id
                           JOIN movies m ON s.movie_id = m.id");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $theater_id = $_POST['theater_id'];
    $movie_id = $_POST['movie_id'];
    $show_time = $_POST['show_time'];

    $query = "INSERT INTO showtimes (theater_id, movie_id, show_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $theater_id, $movie_id, $show_time);
    
    if ($stmt->execute()) {
        echo "Showtime added successfully!";
    } else {
        echo "Error adding showtime.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Showtimes | TikShow</title>
</head>
<body>

<h2>Add a New Showtime</h2>
<form method="POST">
    <label>Select Theater:</label>
    <select name="theater_id" required>
        <?php while ($row = $theaters->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Select Movie:</label>
    <select name="movie_id" required>
        <?php while ($row = $movies->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['title'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Show Time:</label>
    <input type="time" name="show_time" required>

    <button type="submit">Add Showtime</button>
</form>

<h2>Existing Showtimes</h2>
<table border="1">
    <tr>
        <th>Theater</th>
        <th>Movie</th>
        <th>Show Time</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $showtimes->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['theater_name']) ?></td>
        <td><?= htmlspecialchars($row['movie_title']) ?></td>
        <td><?= htmlspecialchars($row['show_time']) ?></td>
        <td>
            <a href="edit_showtime.php?id=<?= $row['id'] ?>">Edit</a> | 
            <a href="delete_showtime.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this showtime?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
