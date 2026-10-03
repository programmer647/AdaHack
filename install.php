<?php

include_once "connection.php";

$stmt = $conn->prepare("DROP TABLE IF EXISTS Tbltest;
CREATE TABLE Tbltest (
    Id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CompanyName VARCHAR(255) NOT NULL,
    Year INT NOT NULL,
    Transport INT NOT NULL,
    Energy INT NOT NULL,
    TotalCO2e INT NOT NULL)");
$stmt->execute();

?>