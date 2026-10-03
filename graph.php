<?php
session_start();
include_once "connection.php";

if (empty($_SESSION['company_name'])) {
    header('Location: company.php');
    exit;
}

$categoryDataPoints = [];
$yearlyDataPoints = [];
$yearOptions = [];

$stmt = $conn->prepare("SELECT DISTINCT year
    FROM Tbltest
    WHERE CompanyName = :companyName
    ORDER BY year DESC");
$stmt->bindParam(':companyName', $_SESSION['company_name']);
$stmt->execute();
while ($yearRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $yearOptions[] = (int)$yearRow['year'];
}

$selectedYear = !empty($_GET['year']) ? (int)$_GET['year'] : null;
if ($selectedYear === null && !empty($yearOptions)) {
    $selectedYear = $yearOptions[0];
}

if ($selectedYear !== null) {
    $stmt = $conn->prepare("SELECT CompanyName, year, Transport, Energy, TotalCO2e
        FROM Tbltest
        WHERE CompanyName = :companyName AND year = :year
        ORDER BY year DESC
        LIMIT 1");
    $stmt->bindParam(':companyName', $_SESSION['company_name']);
    $stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $categoryDataPoints = [
            ["y" => (float)$row['Transport'], "label" => "Transport"],
            ["y" => (float)$row['Energy'], "label" => "Energy"]
        ];
    }
}

$yearlyStmt = $conn->prepare("SELECT year, SUM(TotalCO2e) AS totalCO2e
    FROM Tbltest
    GROUP BY year
    ORDER BY year ASC");
$yearlyStmt->execute();
while ($row = $yearlyStmt->fetch(PDO::FETCH_ASSOC)) {
    $yearlyDataPoints[] = [
        "label" => (string)$row['year'],
        "y" => (float)$row['totalCO2e']
    ];
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carbon Footprint Results</title>
    <link rel="stylesheet" href="style.css">
    <script>
        window.onload = function() {
            var categoryChart = new CanvasJS.Chart("categoryChartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Carbon Footprint by Category (<?php echo htmlspecialchars((string)$selectedYear); ?>)"
                },
                axisY: {
                    title: "Kg CO2e"
                },
                data: [{
                    type: "column",
                    yValueFormatString: "#,##0.## Kg",
                    dataPoints: <?php echo json_encode($categoryDataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            categoryChart.render();

            var yearlyChart = new CanvasJS.Chart("yearlyChartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Carbon Footprint Year on Year"
                },
                axisY: {
                    title: "Kgs CO2e"
                },
                axisX: {
                    title: "Reporting Year"
                },
                data: [{
                    type: "line",
                    lineThickness: 3,
                    markerSize: 6,
                    yValueFormatString: "#,##0.## Kgs",
                    dataPoints: <?php echo json_encode($yearlyDataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            yearlyChart.render();
        }
    </script>
</head>
<body>
    <h1>Carbon Footprint Results</h1>

    <?php if (!empty($_SESSION['last_calculation'])): ?>
        <p>
            Last submission: <strong><?php echo htmlspecialchars($_SESSION['last_calculation']['company']); ?></strong>
            in <?php echo htmlspecialchars((string)$_SESSION['last_calculation']['year']); ?>
            total: <strong><?php echo number_format((float)$_SESSION['last_calculation']['total_tonnes'], 2); ?> tonnes CO2e</strong>
        </p>
    <?php endif; ?>

    <form method="GET" action="graphtest.php" style="margin-bottom: 20px;">
        <label for="year">Select year:</label>
        <select name="year" id="year" onchange="this.form.submit()">
            <?php foreach ($yearOptions as $year): ?>
                <option value="<?php echo (int)$year; ?>" <?php echo ($selectedYear === (int)$year) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars((string)$year); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <div id="categoryChartContainer" style="height: 370px; width: 100%;"></div>
    <div id="yearlyChartContainer" style="height: 370px; width: 100%; margin-top: 30px;"></div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>



<h1>Suggestions for reducing carbon footprint</h1>

<h2>Transport</h2>


<?php

$stmt=$conn->Prepare("SELECT Petrolcars,Dieselcars FROM Tbltest WHERE CompanyName=:companyName AND Year=:year");
$stmt->bindParam(':companyName', $_SESSION['company_name']);
$stmt->bindParam(':year', $selectedYear, PDO::PARAM_INT);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);

$carmiles=$row['Petrolcars'] + $row['Dieselcars'];
$electric_car_emissions=$carmiles * 0.00408;


echo("In ".$selectedYear.", your company emitted ".number_format($carmiles * 0.27696 + $carmiles * 0.21014, 2)." kg CO2e from petrol and diesel cars.");
echo("<br>");
echo("<br>");
echo("If you drove an electric car instead of petrol/diesel, you would have saved ".number_format($carmiles * 0.27696 + $carmiles * 0.21014 - $electric_car_emissions, 2)." kg CO2e in ".$selectedYear.".");

?>

<br>
<br>
<br>
<button onclick="window.location.href='form.php'">Submit data from a different year</button>


</html>