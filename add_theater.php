<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");

// Handle Theater Insertion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $query = "INSERT INTO theaters(name,location,city,latitude,longitude)  VALUES (?, ?, ?, ?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $location, $city, $latitude, $longitude);

    if ($stmt->execute()) {
        echo "Theater added successfully!";
    } else {
        echo "Error adding theater.";
    }
}

// Fetch all theaters
$result = $conn->query("SELECT * FROM theaters");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Theaters | TikShow</title>
</head>
<body>

<h2>Add a New Theater</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Theater Name" required>
    <input type="text" name="location" placeholder="Location" required>
    <input type="text" name="city" placeholder="City" required>
    <input type="text" name="latitude" placeholder="latitude" required>
    <input type="text" name="longitude" placeholder="longitude" required>

    <button type="submit">Add Theater</button>
</form>

<h2>Existing Theaters</h2>
<table border="1">
    <tr>
        <th>Name</th>
        <th>Location</th>
        <th>City</th>
        <th>Actions</th>
    </tr>
    <?php while ($theater = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($theater['name']) ?></td>
        <td><?= htmlspecialchars($theater['location']) ?></td>
        <td><?= htmlspecialchars($theater['city']) ?></td>
        <td>
            <a href="edit_theater.php?id=<?= $theater['id'] ?>">Edit</a> | 
            <a href="delete_theater.php?id=<?= $theater['id'] ?>" onclick="return confirm('Are you sure you want to delete this theater?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
