<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");

$theater_id = $_GET['id'] ?? '';

if (!$theater_id) {
    die("Invalid theater ID.");
}

// Fetch existing theater details
$result = $conn->query("SELECT * FROM theaters WHERE id=$theater_id");
$theater = $result->fetch_assoc();

if (!$theater) {
    die("Theater not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];


    $query = "UPDATE theaters SET name=?, location=?, city=?, latitude=?,longitude=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $name, $location, $city, $latitude,$longitude, $theater_id);

    if ($stmt->execute()) {
        echo "Theater updated successfully!";
        header("Location: theaters.php"); // Redirect after update
        exit();
    } else {
        echo "Error updating theater.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Theater</title>
</head>
<body>

<h2>Edit Theater</h2>

<form method="POST">
    <label>Theater Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($theater['name']) ?>" required><br>

    <label>Location:</label>
    <input type="text" name="location" value="<?= htmlspecialchars($theater['location']) ?>" required><br>

    <label>City:</label>
    <input type="text" name="city" value="<?= htmlspecialchars($theater['city']) ?>" required><br>

    <label>latitude:</label>
    <input type="text" name="latitude" value="<?= htmlspecialchars($theater['latitude']) ?>" required><br>
    <label>longitude:</label>
    <input type="text" name="longitude" value="<?= htmlspecialchars($theater['longitude']) ?>" required><br>


    <button type="submit">Update Theater</button>
</form>

<!-- Delete Theater Button -->
<form method="POST" action="delete_theater.php" onsubmit="return confirm('Are you sure you want to delete this theater?');">
    <input type="hidden" name="id" value="<?= $theater_id ?>">
    <button type="submit" style="background: red; color: white;">Delete Theater</button>
</form>

<!-- Back to Theaters List -->
<p><a href="theaters.php">Back to Theaters</a></p>

</body>
</html>
