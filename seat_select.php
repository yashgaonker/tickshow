<?php
include 'db.php';
// include 'header.php';

if (!isset($_GET['movie_id'])) {
    echo "<p class='error-msg'>Invalid movie selection.</p>";
    exit;
}

$movie_id = $_GET['movie_id'];
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
    <title>Seat Selection - TikShow</title>
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
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Header */
header {
    background: rgba(0, 0, 0, 0.9);
    padding: 15px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
}

.logo {
    font-size: 24px;
    font-weight: bold;
    margin-left: 30px;
    color: #e50914;
}

nav ul {
    list-style: none;
    display: flex;
    margin-right: 30px;
}

nav ul li {
    margin-right: 20px;
}

nav ul li a {
    text-decoration: none;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    transition: 0.3s;
    font-size: 16px;
}

nav ul li a:hover {
    background-color: #e50914;
}

/* Seat Selection Section */
.seat-selection {
    padding: 120px 20px 60px;
    text-align: center;
}

.seat-selection h1 {
    font-size: 28px;
    margin-bottom: 25px;
    color: #e50914; /* Red title */
}

/* Seat Map */
.seat-map {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    gap: 8px;
    margin: 20px auto;
    max-width: 520px;
    position: relative;
}

/* Seat Labels (Front & Back) */
.seat-map::before {
    content: "Back"; /* Swapped */
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
    font-weight: bold;
    color: white;
}

.seat-map::after {
    content: "Front"; /* Swapped */
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 18px;
    font-weight: bold;
    color: white;
}

/* Seats */
.seat {
    width: 42px;
    height: 42px;
    background-color: #d3d3d3; /* Light gray for better visibility */
    border-radius: 5px;
    cursor: pointer;
    transition: 0.2s;
    font-size: 14px;
    font-weight: bold;
    color: black; /* Text visibility */
    display: flex;
    align-items: center;
    justify-content: center;
}

.seat:hover {
    transform: scale(1.1);
    background-color: #b0b0b0; /* Slightly darker gray */
}

.seat.selected {
    background-color: #e50914;
    color: white;
}

.seat.booked {
    background-color: black;
    color: white;
    cursor: not-allowed;
}

/* Confirm Button */
.confirm-btn {
    background: #e50914;
    color: white;
    padding: 12px 30px;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    font-size: 18px;
    margin-top: 20px;
    transition: 0.3s;
}

.confirm-btn:hover {
    background: #c20812;
}

/* Footer */
footer {
    text-align: center;
    padding: 15px;
    background: black;
    position: fixed;
    width: 100%;
    bottom: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .seat-map {
        grid-template-columns: repeat(5, 1fr);
        max-width: 300px;
    }

    .seat {
        width: 35px;
        height: 35px;
        font-size: 12px;
    }

    .seat-selection h1 {
        font-size: 24px;
    }

    .confirm-btn {
        font-size: 16px;
        padding: 10px 25px;
    }

    nav ul {
        flex-direction: column;
        gap: 10px;
    }

    header {
        flex-direction: column;
        text-align: center;
    }
}

    </style>

   
</head>
<body>
    <header>
        <div class="logo">TikShow</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#">Movies</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Concerts</a></li>
                <li><a href="#">Theatres</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="seat-selection">
            <h1>Select Your Seats for "<?php echo htmlspecialchars($movie['title']); ?>"</h1>
            <div class="seat-map" id="seatMap">
                <!-- Seats will be generated here -->
            </div>
            
            <!-- Booking Form -->
            <form id="bookingForm" action="booking.php" method="POST">
                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                <input type="hidden" name="selected_seats" id="selectedSeatsInput">
                <button type="submit" class="confirm-btn" onclick="return prepareBooking()">Confirm Seats</button>
            </form>
        </div>
    </main>

    <footer>
        <p>Copyright 2025 &copy; TikShow Pvt. Ltd.</p>
    </footer>

    <script>
        const seatMap = document.getElementById('seatMap');
        const selectedSeats = [];

        // Generate seats
        for (let i = 1; i <= 100; i++) {
            const seat = document.createElement('div');
            seat.classList.add('seat');
            seat.textContent = i;
            seat.addEventListener('click', () => selectSeat(seat));
            seatMap.appendChild(seat);
        }

        function selectSeat(seat) {
            if (seat.classList.contains('booked')) {
                return;
            }
            seat.classList.toggle('selected');
            const seatNumber = seat.textContent;
            if (selectedSeats.includes(seatNumber)) {
                selectedSeats.splice(selectedSeats.indexOf(seatNumber), 1);
            } else {
                selectedSeats.push(seatNumber);
            }
        }

        function prepareBooking() {
        if (selectedSeats.length === 0) {
            alert("Please select at least one seat.");
            return false; // Prevent form submission
        }
        document.getElementById("selectedSeatsInput").value = selectedSeats.join(',');
        return true; // Allow form submission
    }

            
 
    </script>
</body>
</html>
