<?php
include 'db.php';
// include 'header.php';


session_start();
include 'header.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TikShow - Movie Ticket Booking</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link  rel ="stylesheet" href="bootstrap.css.min">
    <style>
         body {
            background:  #4B0082; /* Fallback gradient */
            /* background: url('assets/images/background.webp') no-repeat center center fixed; */
            background-size: cover; /* Ensures the image fills the entire screen */
            background-attachment: fixed;
            color: white;
            overflow-x: hidden;
        } */
        .floating {
            animation: floatAnimation 6s infinite alternate ease-in-out;
        }
        @keyframes floatAnimation {
            from { transform: translateY(0px); }
            to { transform: translateY(10px); }
        }
    </style>
    <style>
/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #2b0a3d, #000000);
    color: white;
    overflow-x: hidden;
}



.logo {
    font-size: 28px;
    font-weight: bold;
    margin-left: 30px;
    color: #e50914;
    text-shadow: 2px 2px 10px #e50914;
}



/* Hero Section */
.hero {
    height: 350px;
    background: url('../images/hero-bg.jpg') no-repeat center center/cover;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: inset 0px 0px 50px rgba(0, 0, 0, 0.5);
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
}

.hero-content {
    position: relative;
    z-index: 10;
    animation: fadeIn 2s ease-in-out;
}

.hero h1 {
    font-size: 45px;
    font-weight: bold;
    text-shadow: 0px 0px 20px #e50914;
}

.hero p {
    font-size: 18px;
    margin: 10px 0;
}

.neon-btn {
    display: inline-block;
    padding: 12px 25px;
    font-size: 18px;
    color: white;
    background: #6a0dad;
    border-radius: 5px;
    text-decoration: none;
    transition: 0.3s ease-in-out;
    box-shadow: 0px 0px 15px #6a0dad;
}

.neon-btn:hover {
    background: #e50914;
    box-shadow: 0px 0px 20px #e50914;
    transform: scale(1.05);
}

/* Now Showing Section */
.featured-movies {
    padding: 50px;
    text-align: center;
}

.featured-movies h2 {
    font-size: 28px;
    margin-bottom: 20px;
}

.movie-list {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

.movie-card {
    background: #222;
    border-radius: 10px;
    width: 250px;
    overflow: hidden;
    box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease-in-out;
    position: relative;
}

.movie-card:hover {
    transform: scale(1.05);
    box-shadow: 0px 0px 15px #6a0dad;
}

.movie-card img {
    width: 100%;
    height: 350px;
    object-fit: cover;
    filter: brightness(90%);
    transition: filter 0.3s ease-in-out;
}

.movie-card:hover img {
    filter: brightness(100%);
}

.movie-info {
    padding: 15px;
}

.movie-info h3 {
    font-size: 20px;
    margin-bottom: 5px;
}

.movie-info p {
    font-size: 14px;
    color: #ccc;
    margin: 5px 0;
}

/* Floating Animation */
.floating {
    animation: floatAnimation 6s infinite alternate ease-in-out;
}

@keyframes floatAnimation {
    from { transform: translateY(0px); }
    to { transform: translateY(10px); }
}

/* Fade In Animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero h1 {
        font-size: 30px;
    }

    .hero p {
        font-size: 16px;
    }

    .movie-list {
        flex-direction: column;
        align-items: center;
    }

    .movie-card {
        width: 90%;
    }
}
    </style>
</head>
<body>


    <!-- Hero Section -->
    <section class="hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>NOW SHOWING</h1>
            <p>A Movie Ticket Booking Experience Like Never Before</p>
            <a href="movies.php" class="btn neon-btn">Browse Movies</a>
        </div>
    </section>

    <!-- Now Showing Section -->
    <section class="featured-movies">
        <!-- <h2>Now Showing</h2> -->
        <div class="movie-list">
            <?php
            $sql = "SELECT * FROM movies LIMIT 10";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<div class='movie-card'>
                        <img src='uploads/" . $row['poster'] . "' alt='Movie Poster'>
                        <div class='movie-info'>
                            <h3>" . $row['title'] . "</h3>
                            <p>Genre: " . $row['genre'] . "</p>
                            <a href='movie_details.php?id=" . $row['id'] . "' class='btn neon-btn'>View Details</a>
                        </div>
                      </div>";
            }
            ?>
        </div>
    </section>

</body>
</html>

<?php include 'footer.php'; ?>
