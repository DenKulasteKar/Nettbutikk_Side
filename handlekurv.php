<?php require "db_connect.php"; ?>

<?php

$sql = "SELECT produkt_id, namn, beskriving, pris, lagerkvantitet, bilde FROM produkt WHERE produkt_id = ?";

// Sjekkar om økta er starta, og startar ho om nødvendig
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hentar handlekorga frå cookies om ho finst, og lagrar ho i økta
if (!isset($_SESSION['handlekurv']) && isset($_COOKIE['handlekurv'])) {
    $_SESSION['handlekurv'] = json_decode($_COOKIE['handlekurv'], true);
}

// Håndterer tilfelle der eit produkt blir lagt til i handlekorga
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produkt_id'])) {
    $produkt_id = $_POST['produkt_id'];
    $produkt_namn = $_POST['namn'];
    $produkt_pris = $_POST['pris'];

    // Sjekkar om handlekorga finst i økta, om ikkje, lagar ei tom handlekorg
    if (!isset($_SESSION['handlekurv'])) {
        $_SESSION['handlekurv'] = [];
    }

    // Legg til produkt i handlekorga, eller oppdaterer antall om produktet allereie finst
    $_SESSION['handlekurv'][$produkt_id] = [
        'namn' => $produkt_namn,
        'pris' => $produkt_pris,
        'antall' => isset($_SESSION['handlekurv'][$produkt_id]) ? $_SESSION['handlekurv'][$produkt_id]['antall'] + 1 : 1
    ];

    // Lagrar handlekorga i cookies slik at ho varer i 7 dagar
    setcookie('handlekurv', json_encode($_SESSION['handlekurv']), time() + (86400 * 7), "/");

    // Omdirigerer til same side for å oppdatere innhaldet
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<div class="relative">
    <!-- Knapp for å vise handlekorga -->
    <button id="cart-btn" class="text-white px-4 hover:bg-indigo-700 rounded flex items-center">
        🛒 <span class="ml-2">Handlekorg</span>
    </button>

    <!-- Popup-vindauge for handlekorga -->
    <div id="cart-dropdown" class="absolute left-0 mt-2 w-64 bg-white text-indigo-600 shadow-md rounded hidden p-4">
        <p class="text-sm font-semibold">Handlekorga di</p>
        <hr class="my-2">
        
        <?php
        // Viser produkta i handlekorga om ho ikkje er tom
        if (!empty($_SESSION['handlekurv'])) {
            foreach ($_SESSION['handlekurv'] as $produkt_id => $produkt) {
                echo "<p class='text-sm text-gray-600'>" . htmlspecialchars($produkt['namn'] ?? 'Ukjent produkt') . 
     " - " . number_format($produkt['pris'] ?? 0, 2) . " kr" . 
     " (x" . ($produkt['antall'] ?? 1) . ")</p>";
            }
        } else {
            echo "<p class='text-sm text-gray-600'>Ingen varer i handlekurven</p>";
        }
        ?>

        <!-- Knapp for å gå til handlekorg-sida -->
        <a href="handlekurv_vis.php" class="block mt-3 text-center bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700">
            Gå til handlekurv
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cartBtn = document.getElementById("cart-btn");
        const cartDropdown = document.getElementById("cart-dropdown");

        // Viser/skjuler handlekorga når knappen blir trykt på
        cartBtn.addEventListener("click", function (event) {
            event.stopPropagation(); // Hindrar at klikket spreier seg til andre element
            cartDropdown.classList.toggle("hidden");
        });

        // Skjuler handlekorga om brukaren klikkar utanfor
        document.addEventListener("click", function (event) {
            if (!cartDropdown.contains(event.target) && !cartBtn.contains(event.target)) {
                cartDropdown.classList.add("hidden");
            }
        });
    });
</script>
