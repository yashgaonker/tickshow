<?php
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="success-container">
        <h1>🎉 Payment Successful!</h1>
        <p>Your booking for seats <strong><?php echo htmlspecialchars($_GET['seats']); ?></strong> is confirmed.</p>
        <a href="index.php" class="btn">Return to Home</a>
    </div>
</body>
</html>
