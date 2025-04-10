<?php
include 'db.php';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies - TikShow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
       /* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

/* Body Styling */
body {
    background: linear-gradient(135deg, #1a1a2e,rgb(65, 0, 108));
    background-size: cover; 
    background-repeat: no-repeat;
    background-attachment: fixed;
    color: white;
    overflow-x: hidden;
}

/* Logo */
.logo {
    font-size: 28px;
    font-weight: bold;
    margin-left: 30px;
    color: #e50914;
    text-shadow: 2px 2px 10px #e50914;
}

/* Floating Animation */
.floating {
    animation: floatAnimation 6s infinite alternate ease-in-out;
}

@keyframes floatAnimation {
    from { transform: translateY(0px); }
    to { transform: translateY(10px); }
}

/* Movie Posters */
.movie-poster {
    height: 320px;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.movie-poster:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
}

/* Movie Cards */
.card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    backdrop-filter: blur(8px);
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
}

.card:hover {
    transform: scale(1.03);
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
}

/* Card Titles */
.card-title {
    font-size: 20px;
    color: #ffcc00;
    text-shadow: 0 0 10px rgba(255, 204, 0, 0.8);
}

/* Genre Text */
.card-text {
    font-size: 16px;
    color: #d1c4e9;
}

/* Buttons */
.btn-danger {
    background: linear-gradient(45deg, #ff3c3c, #b30000);
    border: none;
    padding: 10px;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s ease-in-out;
    box-shadow: 0 0 10px rgba(255, 60, 60, 0.6);
}

.btn-danger:hover {
    background: linear-gradient(45deg, #ff0000, #7a0000);
    box-shadow: 0 0 20px rgba(255, 60, 60, 1);
}

/* Filter Form */
.form-select {
    background: rgba(255, 255, 255, 0.1);
    color: white !important;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 5px;
    transition: 0.3s ease-in-out;
}

.form-select option {
    background: #1a1a2e;
    color: white;
}

.form-select:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Footer */
footer {
    background-color: black;
    padding: 20px;
    margin-top: 20px;
    text-align: center;
}

.social-icons a {
    color: white;
    margin: 0 10px;
    text-decoration: none;
}
    </style>
</head>
<body class="bg-dark text-white">

<div class="container py-5">
    <h2 class="text-center mb-4 floating">All Movies</h2>

    <!-- Filter Form -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-4">
            <form method="GET" action="movies.php" class="d-flex gap-2 floating">
                <select name="genre" class="form-select">
                    <option value="">All Genres</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Comedy">Comedy</option>
                    <option value="Drama">Drama</option>
                    <option value="Horror">Horror</option>
                    <option value="Romance">Romance</option>
                    <option value="Sci-Fi">Sci-Fi</option>
                    <option value="Thriller">Thriller</option>
                    <option value="Animation">Animation</option>
                    <option value="Fantasy">Fantasy</option>
                </select>
                <button type="submit" class="btn btn-danger">Filter</button>
            </form>
        </div>
    </div>

    <!-- Movies Grid -->
    <div class="container">
        <div class="row">
            <?php
            $sql = "SELECT * FROM movies";
            if (!empty($_GET['genre'])) {
                $genre = $_GET['genre'];
                $stmt = $conn->prepare("SELECT * FROM movies WHERE genre = ?");
                $stmt->bind_param("s", $genre);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $result = $conn->query($sql);
            }

            $count = 0;
            while ($row = $result->fetch_assoc()) {
                if ($count % 3 == 0) {
                    echo '<div class="row justify-content-center">';
                }

                echo "<div class='col-md-4 mb-4 floating'>
                        <div class='card bg-secondary text-white h-100'>
                            <img src='uploads/" . $row['poster'] . "' class='card-img-top movie-poster' alt='Movie Poster'>
                            <div class='card-body'>
                                <h2 class='card-title'>" . htmlspecialchars($row['title']) . "</h2>
                                <p class='card-text'>Genre: " . htmlspecialchars($row['genre']) . "</p>
                                <a href='movie_details.php?id=" . $row['id'] . "' class='btn btn-danger w-100'>View Details</a>
                            </div>
                        </div>
                      </div>";

                $count++;

                if ($count % 3 == 0) {
                    echo '</div>';
                }
            }

            if ($count % 3 != 0) {
                echo '</div>';
            }

            if (isset($stmt)) {
                $stmt->close();
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>

<?php include 'footer.php'; ?>
