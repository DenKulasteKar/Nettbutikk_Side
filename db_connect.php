<?php
$servername = "localhost"; // Database-server
$username = "root";        // Database-brukarnamn
$password = "root";            // Database-passord
$dbname = "nettbutikk";  // Database-namn

// Opprett tilkopling
$conn = new mysqli($servername, $username, $password, $dbname);

// Sjekk tilkopling
if ($conn->connect_error) {
    die("Tilkopling feila: " . $conn->connect_error);
}
?>