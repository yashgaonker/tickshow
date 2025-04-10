<?php
include 'db.php';

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

// Fetch all bookings
$sql = "SELECT bookings.id, users.full_name AS user_name, movies.title AS movie_title, bookings.selected_seats 
        FROM bookings 
        JOIN users ON bookings.user_id = users.id 
        JOIN movies ON bookings.movie_id = movies.id 
        ORDER BY bookings.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TikShow</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2b0a3d, #000000);
            color: white;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .admin {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(255, 0, 127, 0.5);
        }
        h1, h2 {
            color: #ff007f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        th {
            background: #ff007f;
            color: white;
        }
        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }
        .admin-buttons a {
            display: inline-block;
            background: #ff007f;
            color: white;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }
        .admin-buttons a:hover {
            background: #ff3399;
        }
        a[href*="delete_booking.php"] {
            background: #ff0000;
            padding: 5px 10px;
            border-radius: 5px;
        }
        a[href*="delete_booking.php"]:hover {
            background: #cc0000;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-left: 30px;
            color: #e50914;
        }
    </style>
</head>
<body>
    <div class="logo">TIKSHOW</div>
    <section class="admin">
        <h1>Admin Dashboard</h1>
        <h2>Bookings</h2>

        <div class="admin-buttons">
            <a href="add_movies.php">Add Movies</a>
            <a href="add_offers.php">Add Offers</a>
            <a href="add_theater.php">Add Theater</a>
            <a href="add_showtime.php">Add Showtime</a>
            <a href="adminlogout.php">Logout</a>
        </div>

        <table border="1">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Movie</th>
                <th>Seat</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['movie_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['selected_seats']); ?></td> <!-- FIXED SEAT ISSUE -->
                    <td><a href="delete_movie.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>

<?php include 'footer.php'; ?>
