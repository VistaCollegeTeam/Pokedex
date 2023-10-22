<?php
$servername = "localhost";
$username = "root";  // Update deze met je MySQL username
$password = "";      // Update deze met je MySQL password
$dbname = "pokedex_db"; // Naam van je database

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
