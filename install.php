<?php

include_once "connection.php";

$stmt = $conn->prepare("DROP TABLE IF EXISTS Tbltest;
CREATE TABLE Tbltest (
    Id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CompanyName VARCHAR(255) NOT NULL,
    Year INT NOT NULL,
    Dieselcars INT NOT NULL,
    Petrolcars INT NOT NULL,
    Shorthaul INT NOT NULL,
    Longhaul INT NOT NULL,
    Transport INT NOT NULL,
    Electricity INT NOT NULL,
    Gas INT NOT NULL,
    Energy INT NOT NULL,
    TotalCO2e INT NOT NULL,
    Revenue INT NOT NULL)");
$stmt->execute();

?>