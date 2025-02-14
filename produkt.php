<?php
require "db_connect.php";
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-100">
    
    <div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-indigo-900 mb-4">Produkt</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            $sql = "SELECT produkt_id, namn, beskriving, pris, lagerkvantitet, bilde FROM produkt";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='bg-indigo-100 p-4 rounded-lg shadow-md'>
                            <a href='produktinfo.php?produkt_id={$row['produkt_id']}' class='block'>
                                <img src='../{$row['bilde']}' alt='{$row['namn']}' class='w-full h-40 object-cover rounded-md'>
                                <h2 class='text-lg font-bold mt-2'>{$row['namn']}</h2>
                            </a>
                            <p class='text-sm text-gray-700'>{$row['beskriving']}</p>
                            <p class='text-indigo-900 font-bold mt-2'>{$row['pris']} kr</p>
                            <p class='text-gray-600 text-sm'>Lager: {$row['lagerkvantitet']}</p>
                            
                            <!-- Knapp for å legge til i handlekurven -->
                            <form method='post' action='handlekurv.php'>
                                <input type='hidden' name='produkt_id' value='{$row['produkt_id']}'>
                                <input type='hidden' name='navn' value='{$row['namn']}'>
                                <input type='hidden' name='pris' value='{$row['pris']}'>
                                <button type='submit' class='mt-3 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 w-full'>
                                    Kjøp No!!!
                                </button>
                            </form>
                          </div>";
                }
            } else {
                echo "<p class='text-center p-4'>Ingen produkt funne</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- JavaScript for mobilmenyen -->
    <script>
        document.getElementById("menu-btn").addEventListener("click", function() {
            document.getElementById("menu").classList.toggle("hidden");
        });
    </script>
</body>
</html>
