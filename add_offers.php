<?php
include 'db.php';

// Predefined offer names
$offer_names = ["New Year Deal", "Weekend Special", "Student Discount", "Flash Sale", "Holiday Offer"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $offer_name = $_POST['offer_name'];
    $description = $_POST['description'];
    $discount_percentage = $_POST['discount_percentage'];
    $promo_code = strtoupper($_POST['promo_code']);
    $valid_until = $_POST['valid_until'];

    // Check if offer name already exists
    $check_sql = "SELECT id FROM offers WHERE offer_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $offer_name);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $message = "Offer name already exists! Choose a different name.";
    } else {
        // Insert new offer
        $sql = "INSERT INTO offers (offer_name, description, discount_percentage, promo_code, valid_until) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $offer_name, $description, $discount_percentage, $promo_code, $valid_until);
        
        if ($stmt->execute()) {
            $message = "Offer added successfully!";
        } else {
            $message = "Error adding offer: " . $conn->error;
        }
    }
}

// Fetch all offers
$offers = $conn->query("SELECT * FROM offers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Offers</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #1a1a2e;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .form-container, .table-container {
            width: 50%;
            margin: auto;
            padding: 20px;
            background: #ffcc00;
            color: black;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }
        select, textarea, input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: black;
            color: #ffcc00;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #ff9900;
            color: black;
        }
        table {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            color: black;
        }
        table, th, td {
            border: 1px solid black;
            padding: 10px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-left: 30px;
            color: #e50914;
        }
        .action-links a {
            text-decoration: none;
            color: blue;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="logo">TIKSHOW</div>
    <h1>Admin - Manage Offers</h1>

    <?php if (isset($message)) echo "<p style='color: red;'>$message</p>"; ?>

    <div class="form-container">
        <h2>Add New Offer</h2>
        <form method="POST">
            <label>Offer Name:</label>
            <select name="offer_name" required>
                <option value="">-- Select Offer --</option>
                <?php foreach ($offer_names as $offer) { ?>
                    <option value="<?php echo htmlspecialchars($offer); ?>"><?php echo htmlspecialchars($offer); ?></option>
                <?php } ?>
            </select>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Discount Percentage:</label>
            <input type="number" name="discount_percentage" min="1" max="100" required>

            <label>Promo Code:</label>
            <input type="text" name="promo_code" required>

            <label>Valid Until:</label>
            <input type="date" name="valid_until" required>

            <button type="submit">Add Offer</button>
        </form>
    </div>

    <h2>Existing Offers</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>Offer Name</th>
                <th>Description</th>
                <th>Discount</th>
                <th>Promo Code</th>
                <th>Valid Until</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $offers->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['offer_name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['discount_percentage']) ?>%</td>
                <td><?= htmlspecialchars($row['promo_code']) ?></td>
                <td><?= htmlspecialchars($row['valid_until']) ?></td>
                <td class="action-links">
                    <a href="edit_offer.php?id=<?= $row['id'] ?>">Edit</a> | 
                    <a href="delete_offer.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this offer?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
