<?php
include 'db.php';

// Check if offer ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Offer ID.");
}

$offer_id = intval($_GET['id']);

// Fetch offer details
$sql = "SELECT * FROM offers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $offer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Offer not found.");
}

$offer = $result->fetch_assoc();
$stmt->close();

// Predefined offer names
$offer_names = ["New Year Deal", "Weekend Special", "Student Discount", "Flash Sale", "Holiday Offer"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $offer_name = $_POST['offer_name'];
    $description = $_POST['description'];
    $discount_percentage = $_POST['discount_percentage'];
    $promo_code = strtoupper($_POST['promo_code']);
    $valid_until = $_POST['valid_until'];

    // Update the offer
    $update_sql = "UPDATE offers SET offer_name=?, description=?, discount_percentage=?, promo_code=?, valid_until=? WHERE id=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssissi", $offer_name, $description, $discount_percentage, $promo_code, $valid_until, $offer_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Offer updated successfully!'); window.location.href='manage_offers.php';</script>";
    } else {
        echo "Error updating offer: " . $conn->error;
    }
    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Offer</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #1a1a2e;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .form-container {
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
    </style>
</head>
<body>

    <h1>Edit Offer</h1>

    <div class="form-container">
        <form method="POST">
            <label>Offer Name:</label>
            <select name="offer_name" required>
                <?php foreach ($offer_names as $offer_name) { ?>
                    <option value="<?= htmlspecialchars($offer_name) ?>" <?= $offer['offer_name'] == $offer_name ? 'selected' : '' ?>>
                        <?= htmlspecialchars($offer_name) ?>
                    </option>
                <?php } ?>
            </select>

            <label>Description:</label>
            <textarea name="description" required><?= htmlspecialchars($offer['description']) ?></textarea>

            <label>Discount Percentage:</label>
            <input type="number" name="discount_percentage" min="1" max="100" value="<?= $offer['discount_percentage'] ?>" required>

            <label>Promo Code:</label>
            <input type="text" name="promo_code" value="<?= htmlspecialchars($offer['promo_code']) ?>" required>

            <label>Valid Until:</label>
            <input type="date" name="valid_until" value="<?= $offer['valid_until'] ?>" required>

            <button type="submit">Update Offer</button>
        </form>
    </div>

</body>
</html>
