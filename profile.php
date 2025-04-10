<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tickshow";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT id, full_name, email, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $full_name, $email, $profile_picture);
    $stmt->fetch();
}
$stmt->close();

$query = "
    SELECT b.id, b.booking_date, b.status, b.selected_seats, b.total_price, 
           m.title AS movie_name, m.showtime AS event_date, m.price AS ticket_price
    FROM bookings b
    JOIN movies m ON b.movie_id = m.id
    WHERE b.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, rgb(40, 10, 70), rgb(90, 20, 60));
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }
        .profile-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3498db;
            margin-bottom: 15px;
        }
        h2 {
            color: #2c3e50;
        }
        .info {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .logout-btn {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .history-container {
            margin-top: 20px;
            width: 80%;
            max-width: 800px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #3498db;
            color: white;
            padding: 10px;
        }
        td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <img src="<?php echo htmlspecialchars(!empty($profile_picture) ? $profile_picture : 'profile_image/default.png'); ?>" alt="Profile Picture" class="profile-pic">
        <h2><?php echo htmlspecialchars($full_name); ?></h2>
        <p class="info"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="history-container">
        <h2>Ticket Purchase History</h2>
        <table>
            <tr>
                <th>Movie</th>
                <th>Showtime</th>
                <th>Seats</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo isset($row['movie_name']) ? htmlspecialchars($row['movie_name']) : 'No Data'; ?></td>
                <td><?php echo isset($row['event_date']) ? htmlspecialchars($row['event_date']) : 'No Data'; ?></td>
                <td><?php echo isset($row['selected_seats']) ? htmlspecialchars($row['selected_seats']) : 'No Data'; ?></td>
                <td><?php echo isset($row['ticket_price']) ? "$" . htmlspecialchars($row['ticket_price']) : 'No Data'; ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
<?php include 'footer.php'; ?>