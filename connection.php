<?php

//sets the variables needed to log on to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "adahack";

//this code tries to connect to the database
try {
    //connects to the database by passing in the variables
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();//if the connection fails then it prints a message
    }
?>
