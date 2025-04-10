<?php
include 'db.php';
session_start();

if (!isset($_POST['movie_id'], $_POST['selected_seats'], $_POST['total_price'])) {
    header("Location: index.php");
    exit;
}

$movie_id = intval($_POST['movie_id']);
$selected_seats = htmlspecialchars($_POST['selected_seats']);
$total_price = floatval($_POST['total_price']);

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - TikShow</title>
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

/* Payment Container */
.payment-container {
    background: rgba(0, 0, 0, 0.9);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0px 6px 15px rgba(255, 255, 255, 0.2);
    max-width: 600px;
    width: 90%;
}

.payment-container h1 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #e50914;
}

.payment-container p {
    font-size: 20px;
    margin-bottom: 15px;
}

.payment-container strong {
    color: #ffcc00;
    font-size: 22px;
}

/* Confirm Button */
.confirm-btn {
    background: #28a745;
    color: white;
    padding: 15px 40px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-size: 20px;
    margin-top: 20px;
    transition: 0.3s;
    display: inline-block;
}

.confirm-btn:hover {
    background: #218838;
}

/* Success Message */
.success-msg {
    display: none;
    color: #28a745;
    font-size: 22px;
    font-weight: bold;
    margin-top: 20px;
}

/* Loader Animation */
.loader {
    display: none;
    border: 6px solid rgba(255, 255, 255, 0.2);
    border-top: 6px solid #28a745;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .payment-container {
        max-width: 95%;
        padding: 30px;
    }

    .payment-container h1 {
        font-size: 26px;
    }

    .payment-container p {
        font-size: 18px;
    }

    .payment-container strong {
        font-size: 20px;
    }

    .confirm-btn {
        font-size: 18px;
        padding: 12px 30px;
    }
}

    </style>
    <style>
        .payment-container { text-align: center; max-width: 500px; margin: 50px auto; padding: 20px; background: rgba(0, 0, 0, 0.7); border-radius: 10px; color: white; }
        .confirm-btn { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; transition: 0.3s; font-size: 18px; }
        .confirm-btn:hover { background: #218838; }
        .success-msg { display: none; color: #28a745; font-size: 22px; font-weight: bold; margin-top: 20px; }
        .loader { display: none; border: 6px solid #f3f3f3; border-top: 6px solid #28a745; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <main>
        <div class="payment-container">
            <h1>Payment for "<?php echo htmlspecialchars($movie['title']); ?>"</h1>
            <p><strong>Selected Seats:</strong> <?php echo $selected_seats; ?></p>
            <p><strong>Total Amount:</strong> Rs.<?php echo number_format($total_price, 2); ?></p>
            <button id="payNowBtn" class="confirm-btn">Pay Now</button>
            <div id="loader" class="loader"></div>
            <p id="successMsg" class="success-msg">✅ Payment Successful! Redirecting...</p>
        </div>
    </main>

    <script>
        document.getElementById("payNowBtn").addEventListener("click", function() {
            document.getElementById("payNowBtn").style.display = "none";
            document.getElementById("loader").style.display = "block";
            setTimeout(function() {
                document.getElementById("loader").style.display = "none";
                document.getElementById("successMsg").style.display = "block";
                setTimeout(() => { document.forms["paymentForm"].submit(); }, 2000);
            }, 2000);
        });
    </script>

    <form id="paymentForm" action="process_payment.php" method="POST">
        <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
        <input type="hidden" name="selected_seats" value="<?php echo $selected_seats; ?>">
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
    </form>
</body>
</html>
