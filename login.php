<?php
include 'db_connect.php'; // Knyttar til databasen
require 'navbar.php'; // Inkluderer navbaren

// Sjekkar om skjemaet er sendt inn via POST-metoden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brukarnamn = $_POST['brukarnamn']; // Hentar brukarnamn frå skjemaet
    $passord = $_POST['passord']; // Hentar passord frå skjemaet

    // Søkjer etter brukaren i databasen
    $sql = "SELECT brukar_id, passord FROM brukar WHERE brukarnamn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $brukarnamn);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($brukar_id, $hashed_passord);

    // Sjekkar om brukarnamnet finst, og om passordet er rett
    if ($stmt->fetch() && password_verify($passord, $hashed_passord)) {
        echo "<script>alert('Innlogging vellykket!'); window.location.href='heimside.php';</script>";
        session_start(); // Startar økta
        $_SESSION['brukar_id'] = $brukar_id; // Lagre brukar-ID i økta
        $_SESSION['brukarnamn'] = $brukarnamn; // Lagre brukarnamnet i økta
        exit(); // Stoppar vidare koding etter omdirigering
    } else {
        echo "<script>alert('Feil brukarnamn eller passord.');</script>";
    }

    // Lukkar tilkoplinga til databasen
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logg Inn</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Inkluderer Tailwind CSS -->
</head>
<body class="bg-indigo-100">
    
    <div class="container mx-auto mt-10 p-6 bg-indigo-200 shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-4 text-indigo-900">Logg Inn</h2>

        <!-- Innloggingsskjema -->
        <form method="post" action="" class="space-y-4">
            <div>
                <label class="block text-indigo-900">Brukarnamn:</label>
                <input type="text" name="brukarnamn" required class="border rounded w-full p-2 bg-indigo-50">
            </div>
            <div>
                <label class="block text-indigo-900">Passord:</label>
                <input type="password" name="passord" required class="border rounded w-full p-2 bg-indigo-50">
            </div>
            <div>
                <input type="submit" value="Logg Inn" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            </div>
        </form>    
    </div>

    <!-- JavaScript for mobilmenyen -->
    <script>
        document.getElementById("menu-btn").addEventListener("click", function() {
            document.getElementById("menu").classList.toggle("hidden"); // Viser/gøymer menyen når ein trykker på knappen
        });
    </script>
</body>
</html>
