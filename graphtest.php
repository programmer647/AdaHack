<?php
session_start();
include_once "connection.php";

if (empty($_SESSION['company_name'])) {
    header('Location: company.php');
    exit;
}

$categoryDataPoints = [];
$yearlyDataPoints = [];

$stmt = $conn->prepare("SELECT CompanyName, year, Transport, Energy, TotalCO2e
    FROM Tbltest WHERE CompanyName=:companyName and year=(SELECT MAX(year) FROM Tbltest WHERE CompanyName=:companyName)");
$stmt->bindParam(':companyName', $_SESSION['company_name']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $categoryDataPoints = [
        ["y" => (float)$row['Transport'], "label" => "Transport"],
        ["y" => (float)$row['Energy'], "label" => "Energy"]
    ];
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
    <script>
        window.onload = function() {
            var categoryChart = new CanvasJS.Chart("categoryChartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Carbon Footprint by Category (latest submission)"
                },
                axisY: {
                    title: "Tonnes CO2e"
                },
                data: [{
                    type: "column",
                    yValueFormatString: "#,##0.## tonnes",
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
                    title: "Tonnes CO2e"
                },
                axisX: {
                    title: "Reporting Year"
                },
                data: [{
                    type: "line",
                    lineThickness: 3,
                    markerSize: 6,
                    yValueFormatString: "#,##0.## tonnes",
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

    <div id="categoryChartContainer" style="height: 370px; width: 100%;"></div>
    <div id="yearlyChartContainer" style="height: 370px; width: 100%; margin-top: 30px;"></div>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>