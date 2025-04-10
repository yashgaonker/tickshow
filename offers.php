<?php 
include 'header.php'; // Includes the navbar and session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket Offers</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            /* margin: 50px; */
            background: linear-gradient(135deg, #570869, #0b000b);
            color: #333;
        }
        h1 {
            font-size: 2.5rem;
            color: #fff;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
        button {
            background: #4B0082;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
            margin: 10px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        }
        button:hover {
            background: #06000a;
            transform: scale(1.05);
        }
        /* header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, black, rgb(143, 133, 143));
            padding: 10px 20px;
        } */
        /* .container {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        } */
        img {
            width: 650px;
            height: 400px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
        .not-available {
            font-size: 24px;
            font-weight: bold;
            color: red;
            margin-top: 20px;
        }
        .not-available img {
            height: 300px;
            width: 300px;
            display: block;
            margin: auto;
            border-radius: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <h1>OFFERS</h1>

    <h1>Sorry, No Offers Available</h1>
    <img src="images/nooffer.jpg" alt="Not Available">

    <script>
        function showOptions() {
            document.getElementById('options').style.display = 'block';
            document.getElementById('not-available').style.display = 'none';
        }

        function showOffer() {
            document.getElementById('not-available').style.display = 'none';
        }

        function showNotAvailable() {
            document.getElementById('not-available').style.display = 'block';
            document.getElementById('offer-content').style.display = 'none';
        }
    </script>
</body>
</html>
<?php include 'footer.php'; ?>
