<?php
session_start();
include_once "connection.php";

$stmt = $conn->query("SELECT DISTINCT CompanyName FROM Tbltest ORDER BY CompanyName ASC");
$companies = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Select Company</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-shell">
        <header class="topbar">
            <div class="brand">G<span>Research</span></div>
            <div class="subtle">Carbon footprint dashboard</div>
        </header>

        <div class="panel">
            <h1>Select a company</h1>

            <form action="set_company_session.php" method="POST">
                <label for="company">Company</label>
                <select name="company_name" id="company" required>
                    <option value="">-- Choose a company --</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?php echo htmlspecialchars($company); ?>">
                            <?php echo htmlspecialchars($company); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" value="View report">
            </form>
        </div>
    </div>
</body>
</html>