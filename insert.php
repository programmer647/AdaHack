<?php

include_once "connection.php";

session_start();

$companyName = trim($_POST['name'] ?? '');
$reportingYear = (int)($_POST['year'] ?? date('Y'));

const PETROL_FACTOR = 0.27696; // kg CO2e per mile
const DIESEL_FACTOR = 0.21014; // kg CO2e per mile
const ELECTRICITY_FACTOR = 0.207; // kg CO2e per kWh
const GAS_FACTOR = 0.183; // kg CO2e per kWh

$petrol = $_POST['petrol'] * PETROL_FACTOR;
$diesel = $_POST['diesel'] * DIESEL_FACTOR;
$electricity = $_POST['electricity'] * ELECTRICITY_FACTOR;
$gas = $_POST['gas'] * GAS_FACTOR;
$transport = $petrol + $diesel;
$energy = $electricity + $gas;
$total=$petrol + $diesel + $electricity + $gas;



$stmt = $conn->prepare("INSERT INTO Tbltest (CompanyName,Year,Transport,Energy,TotalCO2e) VALUES (:companyName, :year, :transport, :energy, :totalCO2e)");
$stmt->bindParam(':companyName', $companyName);
$stmt->bindParam(':year', $reportingYear);
$stmt->bindParam(':transport', $transport);
$stmt->bindParam(':energy', $energy);
$stmt->bindParam(':totalCO2e', $total);
$stmt->execute();


header('Location: graphtest.php');
exit;
?>