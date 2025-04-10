<?php
include 'db.php';
session_start(); // Ensure session is started

if (!isset($_POST['movie_id']) || !isset($_POST['selected_seats'])) {
    header("Location: index.php"); // Redirect to home if invalid request
    exit;
}

$movie_id = intval($_POST['movie_id']);
$selected_seats = htmlspecialchars($_POST['selected_seats']);
$seat_count = count(explode(',', $selected_seats));

// Fetch movie details
$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    echo "<p class='error-msg'>Movie not found.</p>";
    exit;
}

$price_per_ticket = $movie['price']; // Get ticket price
$total_price = $price_per_ticket * $seat_count;

// Fetch available theaters
$sql = "SELECT * FROM theaters";
$theaters_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking - TikShow</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
  /* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #4B0082;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    text-align: center;
    padding: 20px;
}

/* Booking Container - Bigger & Centered */
.booking-container {
    background: rgba(0, 0, 0, 0.9);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0px 6px 15px rgba(255, 255, 255, 0.2);
    max-width: 600px;
    width: 90%;
}

.booking-container h1 {
    font-size: 30px;
    margin-bottom: 25px;
    color: #e50914;
}

.booking-container p {
    font-size: 20px;
    margin-bottom: 15px;
}

.booking-container strong {
    color: #ffcc00;
    font-size: 22px;
}

/* Confirm Button */
.confirm-btn {
    background: #e50914;
    color: white;
    padding: 15px 40px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-size: 20px;
    margin-top: 30px;
    transition: 0.3s;
    display: inline-block;
}

.confirm-btn:hover {
    background: #c20812;
}

/* Responsive Design */
@media (max-width: 768px) {
    .booking-container {
        max-width: 95%;
        padding: 30px;
    }

    .booking-container h1 {
        font-size: 26px;
    }

    .booking-container p {
        font-size: 18px;
    }

    .booking-container strong {
        font-size: 20px;
    }

    .confirm-btn {
        font-size: 18px;
        padding: 12px 30px;
    }
    .booking-container label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    font-size: 16px;
}



}
    </style>
</head>
<body>
    <main>
        <div class="booking-container">
            <h1>Confirm Your Booking</h1>
            <p>Movie: <strong><?php echo htmlspecialchars($movie['title']); ?></strong></p>
            <p>Selected Seats: <strong><?php echo $selected_seats; ?></strong></p>
            <p>Price per Ticket: <strong>₹<?php echo number_format($price_per_ticket, 2); ?></strong></p>
<p>Total Price: <strong>₹<?php echo number_format($total_price, 2); ?></strong></p>


            <form action="payment.php" method="POST">
                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                <input type="hidden" name="selected_seats" value="<?php echo $selected_seats; ?>">
                <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                
                <label for="theater" >Select Theater:</label>
                <select name="theater_id" id="theater07" required>
                    <option value="">-- Choose a Theater --</option>
                    <?php while ($theater = $theaters_result->fetch_assoc()): ?>
                        <option value="<?php echo $theater['id']; ?>">
                            <?php echo htmlspecialchars($theater['name']); ?> - <?php echo htmlspecialchars($theater['location']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <button type="submit" class="confirm-btn">Proceed to Payment</button>
            </form>
        </div>
    </main>
</body>
</html>
