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
</head>
<body>
    <h1>Select a Company</h1>

    <form action="setcompany.php" method="POST">
        <label for="company">Company:</label>
        <select name="company_name" id="company" required>
            <option value="">-- Choose a company --</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?php echo htmlspecialchars($company); ?>">
                    <?php echo htmlspecialchars($company); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <input type="submit" value="View Report">
    </form>
</body>
</html>