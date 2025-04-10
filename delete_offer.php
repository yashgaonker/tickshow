<?php
include 'db.php';

$offer_id = $_GET['id'] ?? '';

if ($offer_id) {
    $conn->query("DELETE FROM offers WHERE id=$offer_id");
    header("Location: add_offers.php");
    exit();
} else {
    echo "Invalid offer ID.";
}
?>
