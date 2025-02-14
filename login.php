<?php
include 'db_connect.php';
require 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brukarnamn = $_POST['brukarnamn'];
    $passord = $_POST['passord'];

    $sql = "SELECT brukar_id, passord FROM brukar WHERE brukarnamn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $brukarnamn);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($brukar_id, $hashed_passord);

    if ($stmt->fetch() && password_verify($passord, $hashed_passord)) {
        echo "<script>alert('Innlogging vellykket!'); window.location.href='heimside.php';</script>";
        session_start();
        $_SESSION['brukar_id'] = $brukar_id;
        $_SESSION['brukarnamn'] = $brukarnamn;
        exit();
    } else {
        echo "<script>alert('Feil brukernavn eller passord.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logg Inn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-100">
    
    <div class="container mx-auto mt-10 p-6 bg-indigo-200 shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-4 text-indigo-900">Logg Inn</h2>
        <form method="post" action="" class="space-y-4">
            <div>
                <label class="block text-indigo-900">Brukernavn:</label>
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
            document.getElementById("menu").classList.toggle("hidden");
        });
    </script>
</body>
</html>
