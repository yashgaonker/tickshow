<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tickshow");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $genre = trim($_POST['genre']);
    $duration = intval($_POST['duration']);
    $release_date = $_POST['release_date']; // Added release date
    $showtime = $_POST['showtime'];

    if (!empty($_FILES["poster"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["poster"]["name"]);
        $target_file = $target_dir . $file_name;
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO movies (title, genre, duration, release_date, showtime, poster) VALUES (?, ?, ?, ?, ?, ?)");

            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssisss", $title, $genre, $duration, $release_date, $showtime, $file_name);
            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Execute failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "File upload failed or invalid file type.";
        }
    } else {
        echo "No file uploaded.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <!-- <style>
        body {
            background: linear-gradient(135deg, #2b0a3d, #000000); /* Fallback gradient */
            background: url('assets/images/background.webp') no-repeat center center fixed;
            background-size: cover; /* Ensures the image fills the entire screen */
            background-attachment: fixed;
            color: white;
            overflow-x: hidden;
        }
        .floating {
            animation: floatAnimation 6s infinite alternate ease-in-out;
        }
        @keyframes floatAnimation {
            from { transform: translateY(0px); }
            to { transform: translateY(10px); }
        }
        
    </style> -->
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #2b0a3d, #000000);
            color: white;
            text-align: center;
            padding: 20px;
        }

        /* Form Container */
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            width: 50%;
            margin: auto;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.2);
        }

        h2 {
            margin-bottom: 15px;
            font-size: 24px;
        }

        /* Form Inputs */
        input, select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 16px;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        select {
            cursor: pointer;
        }

        /* File Input */
        input[type="file"] {
            background: none;
            border: none;
            cursor: pointer;
        }

        /* Submit Button */
        button {
            background: #ff007f;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff3399;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                width: 80%;
            }
        }
        /* Dropdown Styling */
    select {
        width: 90%;
        padding: 10px;
        margin: 10px 0;
        border: 2px solid #ff007f;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    /* Style dropdown options */
    select option {
        background: #2b0a3d; /* Dark background */
        color: white; /* White text */
        padding: 10px;
    }

    /* Hover effect for options */
    select option:hover {
        background: #ff007f; /* Highlight color */
    }

    /* Ensure dropdown doesn't take up too much space */
    select:focus {
        outline: none;
        border: 2px solid #ff3399;
    }

    /* Fix large dropdown issue */
    select::-webkit-scrollbar {
        width: 8px;
    }

    select::-webkit-scrollbar-thumb {
        background-color: #ff007f;
        border-radius: 10px;
    }

    select::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
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
    <h2>Add Movie</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Movie Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter Movie Title" required><br>

        <label for="genre">Genre:</label>
        <select id="genre" name="genre" required>
            <option value="">Select Genre</option>
            <option value="Action">Action</option>
            <option value="Adventure">Adventure</option>
            <option value="Comedy">Comedy</option>
            <option value="Drama">Drama</option>
            <option value="Horror">Horror</option>
            <option value="Romance">Romance</option>
            <option value="Sci-Fi">Sci-Fi</option>
            <option value="Thriller">Thriller</option>
            <option value="Animation">Animation</option>
            <option value="Fantasy">Fantasy</option>
        </select><br>

        <label for="duration">Duration (min):</label>
        <input type="number" id="duration" name="duration" placeholder="Enter Duration" required><br>

        <label for="release_date">Release Date:</label>
        <input type="date" id="release_date" name="release_date" required><br>

        <label for="showtime">Showtime:</label>
        <input type="datetime-local" id="showtime" name="showtime" required><br>

        <label for="poster">Upload Poster:</label>
        <input type="file" id="poster" name="poster" accept="image/*" required><br>

        <button type="submit">Add Movie</button>
    </form>

    <?php
// Fetch all movies to display with Edit & Delete links
$conn = new mysqli("localhost", "root", "", "tickshow");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SELECT id, title, genre, duration, release_date, showtime, poster FROM movies";
$result = $conn->query($sql);
?>

<h2>Movie List</h2>
<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; color: white; text-align: left;">
    <tr>
        <th>Poster</th>
        <th>Title</th>
        <th>Genre</th>
        <th>Duration</th>
        <th>Release Date</th>
        <th>Showtime</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><img src="uploads/<?php echo $row['poster']; ?>" width="50" height="50"></td>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['genre']; ?></td>
        <td><?php echo $row['duration']; ?> min</td>
        <td><?php echo $row['release_date']; ?></td>
        <td><?php echo $row['showtime']; ?></td>
        <td>
            <a href="edit_movie.php?id=<?php echo $row['id']; ?>" style="color: yellow;">Edit</a> | 
            <a href="delete_movie.php?id=<?php echo $row['id']; ?>" style="color: red;" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php $conn->close(); ?>

</body>
</html>
