<?php
$conn = new mysqli("localhost", "root", "", "tickshow");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM theaters";
$result = $conn->query($query);

$theaters = [];
while ($row = $result->fetch_assoc()) {
    // If lat/lng not set, fetch using Nominatim
    if (!$row['latitude'] || !$row['longitude']) {
        $address = urlencode($row['location'] . ', ' . $row['city']);
        $url = "https://nominatim.openstreetmap.org/search?q=$address&format=json&limit=1";

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: TikShowApp/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);

        $data = json_decode($response, true);
        if (!empty($data)) {
            $row['latitude'] = $data[0]['lat'];
            $row['longitude'] = $data[0]['lon'];

            // Save to DB
            $stmt = $conn->prepare("UPDATE theaters SET latitude=?, longitude=? WHERE id=?");
            $stmt->bind_param("ddi", $row['latitude'], $row['longitude'], $row['id']);
            $stmt->execute();
        }
    }

    $theaters[] = $row;
}
include"header.php"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Theaters | TikShow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #1a1a2e,rgb(65, 0, 108));  }
        .theater-container { max-width: 900px; margin: auto; }
        .theater-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 30px;
        }
        .map { height: 250px; border-radius: 6px; margin: 15px 0; }
        .dir-btn {
            background: #ff5722;
            color: #fff;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
        h1{
            color:white;
        }
    </style>
</head>
<body>

<div class="theater-container">
    <h1>🎬Theaters </h1>

    <?php foreach ($theaters as $i => $theater): ?>
        <?php $mapId = "map$i"; ?>
        <div class="theater-card">
            <h2><?= htmlspecialchars($theater['name']) ?></h2>
            <p>📍 <?= htmlspecialchars($theater['location']) ?>, <?= htmlspecialchars($theater['city']) ?></p>

            <div id="<?= $mapId ?>" class="map"></div>

            <a href="#" class="dir-btn" id="dirBtn<?= $i ?>" target="_blank">Get Directions</a>
        </div>

        <script>
            const map<?= $i ?> = L.map("<?= $mapId ?>").setView([<?= $theater['latitude'] ?>, <?= $theater['longitude'] ?>], 15);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map<?= $i ?>);

            L.marker([<?= $theater['latitude'] ?>, <?= $theater['longitude'] ?>])
                .addTo(map<?= $i ?>)
                .bindPopup("<?= addslashes($theater['name']) ?>");
        </script>
    <?php endforeach; ?>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<script>
// Only for directions — no display of user location
navigator.geolocation.getCurrentPosition(function(pos) {
    const userLat = pos.coords.latitude;
    const userLng = pos.coords.longitude;

    <?php foreach ($theaters as $i => $theater): ?>
        const dirUrl<?= $i ?> = `https://www.openstreetmap.org/directions?engine=fossgis_osrm_car&route=${userLat},${userLng};<?= $theater['latitude'] ?>,<?= $theater['longitude'] ?>`;
        document.getElementById("dirBtn<?= $i ?>").href = dirUrl<?= $i ?>;
    <?php endforeach; ?>
}, function(err) {
    console.warn("Geolocation not available or denied.");
});
</script>

</body>
</html>
<?php $conn->close(); ?>
<?php include 'footer.php'; ?>