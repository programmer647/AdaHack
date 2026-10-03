<?php
include_once "connection.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carbon Footprint Calculator</title>
</head>
<body>
    <h1>Carbon Footprint Calculator</h1>

    <form action="insert.php" method="POST">
        Company Name:<br>
        <input type="text" name="name" required><br><br>

        Reporting Year:<br>
        <input type="number" name="year" min="2020" max="2100" value="2026" required><br><br>

        <h3>Transport</h3>
        Miles travelled in petrol cars:<br>
        <input type="number" min="0" name="petrol" required><br><br>

        Miles travelled in diesel cars:<br>
        <input type="number" min="0" name="diesel" required><br><br>

        <h3>Energy</h3>
        Yearly electricity consumption (kWh):<br>
        <input type="number" min="0" name="electricity" required><br><br>

        Yearly gas consumption (kWh):<br>
        <input type="number" min="0" name="gas" required><br><br>

        <input type="submit" value="Calculate Carbon Footprint">
    </form>
</body>
</html>
