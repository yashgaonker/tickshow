<?php
include 'db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    echo "<p>Movie not found.</p>";
    exit;
}

$movie_id = $_GET['id'];
$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    echo "<p>Movie not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $movie['title']; ?> - TikShow</title>
    <link rel="stylesheet" href="assets/css/style.css">
  
</head>
<body>
<header>
    <div class="logo">TIKSHOW</div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="movies.php">Movies</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<style>
 /* General Styling */
body {
    font-family: 'Poppins', sans-serif;
    background:  #4B0082;
    color: white;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Movie Details Container */
.movie-details {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
    padding: 50px;
    max-width: 900px;
    margin: 100px auto 40px; /* Push down from the header */
    background: rgba(37, 1, 58, 0.15);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
    transition: 0.3s ease-in-out;
}

/* .movie-details:hover {
    transform: scale(1.02);
} */

/* Movie Poster */
.movie-details img {
    width: 350px;
    height: auto;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    transition: 0.3s ease-in-out;
}

.movie-details img:hover {
    transform: scale(1.05);
}

/* Movie Text Details */
.details {
    flex: 1;
    text-align: left;
}

.details h1 {
    font-size: 30px;
    font-weight: bold;
    color:rgb(255, 255, 255);
    text-shadow: 0 0 15px rgba(255, 204, 0, 0.9);
    margin-bottom: 15px;
}

.details p {
    font-size: 18px;
    margin: 10px 0;
}

.details strong {
    color:rgb(255, 255, 255);
}

/* Book Now Button */
.btn {
    display: inline-block;
    background: linear-gradient(45deg, #ffcc00, #e6b800);
    color: black;
    padding: 12px 26px;
    text-decoration: none;
    font-weight: bold;
    font-size: 18px;
    border-radius: 10px;
    transition: 0.3s ease-in-out;
    box-shadow: 0 0 15px rgba(255, 204, 0, 0.8);
}

.btn:hover {
    background: linear-gradient(45deg, #ffbb00, #d9a800);
    box-shadow: 0 0 25px rgba(255, 204, 0, 1);
    transform: scale(1.07);
}

/* Header */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 50px;
    background: rgba(0, 0, 0, 0.95);
    color: white;
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.15);
}

/* Navigation */
nav {
    flex-grow: 1;
    display: flex;
    justify-content: flex-end;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 30px;
    margin: 0;
    padding: 0;
    align-items: center;
}

nav ul li {
    display: inline-block;
}

nav ul li a {
    text-decoration: none;
    color: white;
    font-size: 18px;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 5px;
    transition: 0.3s ease-in-out;
}

nav ul li a:hover {
    background: #ffcc00;
    color: black;
}

/* Fixed Footer */
footer {
    background: black;
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: auto;
    position: fixed;
    bottom: 0;
    width: 100%;
    box-shadow: 0 -4px 8px rgba(255, 255, 255, 0.1);
}

/* Responsive Fix */
@media (max-width: 768px) {
    .movie-details {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }

    .movie-details img {
        width: 100%;
        max-width: 350px;
    }

    .details h1 {
        font-size: 24px;
    }

    .btn {
        font-size: 16px;
        padding: 10px 20px;
    }

    header {
        flex-direction: column;
        text-align: center;
    }

    nav ul {
        flex-direction: column;
        gap: 10px;
    }
}


</style>

    <section class="movie-details">
        <img src="uploads/<?php echo $movie['poster']; ?>" alt="Movie Poster">
        <div class="details">
            <h1><?php echo $movie['title']; ?></h1>
            <p><strong>Genre:</strong> <?php echo $movie['genre']; ?></p>
            <p><strong>Duration:</strong> <?php echo $movie['duration']; ?> mins</p>
            <p><strong>Release Date:</strong> <?php echo $movie['release_date']; ?></p>
            <p><strong>Price:  <?php echo $movie['price']; ?></strong></p>
            <a href="seat_select.php?movie_id=<?php echo $movie['id']; ?>" class="btn">Book Now</a>
        </div>
    </section>
</body>
</html>

<?php include 'footer.php'; ?>