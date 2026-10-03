<?php
include_once "connection.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carbon Footprint Calculator</title>
  <link rel="stylesheet" href="style.css">

</head>
<body>
    <h1>Carbon Footprint Calculator</h1>

    <form action="insert.php" method="POST">
        Company Name:<br><input type="text" name="name" required><br>
        Reporting Year:<br><input type="number" name="year" max="2026" value="2026" required><br>
        Company Revenue:<br><input type="number" name="revenue" required><br>
        <h3>Transport</h3>
        Miles travelled in petrol cars:<br><input type="number" min="0" name="petrol" required><br>
        Miles travelled in diesel cars:<br><input type="number" min="0" name="diesel" required><br>
        Km travelled on long haul flights in economy class:<br><input type="number" min="0" name="longhaul" required><br>
        Km travelled on short haul flights in economy class:<br><input type="number" min="0" name="shorthaul" required><br>
        <h3>Energy</h3>
        Yearly electricity consumption (kWh):<br><input type="number" min="0" name="electricity" required><br>
        Yearly gas consumption (kWh):<br><input type="number" min="0" name="gas" required><br>
        <input type="submit" value="Calculate Carbon Footprint">
    </form>
</body>
</html>
