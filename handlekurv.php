<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="relative">
    <!-- Handlekurv-knapp -->
    <button id="cart-btn" class="text-white px-4 hover:bg-indigo-700 rounded flex items-center">
        🛒 <span class="ml-2">Handlekorg</span>
    </button>

    <!-- Handlekurv-popup -->
    <div id="cart-dropdown" class="absolute left-0 mt-2 w-56 bg-white text-indigo-600 shadow-md rounded hidden p-4">
        <p class="text-sm font-semibold">Handlekorge din</p>
        <hr class="my-2">
        
        <?php
        if (!empty($_SESSION['handlekurv'])) {
            foreach ($_SESSION['handlekurv'] as $produkt) {
                echo "<p class='text-sm text-gray-600'>" . htmlspecialchars($produkt['navn']) . " - " . number_format($produkt['pris'], 2) . " kr</p>";
            }
        } else {
            echo "<p class='text-sm text-gray-600'>Ingen varer i handlekurven</p>";
        }
        ?>

        <!-- Knapp for å gå til handlekurv-siden -->
        <a href="handlekurv.php" class="block mt-3 text-center bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700">
            Gå til handlekurv
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const cartBtn = document.getElementById("cart-btn");
        const cartDropdown = document.getElementById("cart-dropdown");

        cartBtn.addEventListener("click", function (event) {
            event.stopPropagation(); // Hindrer at klikket bobler opp
            cartDropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", function (event) {
            if (!cartDropdown.contains(event.target) && !cartBtn.contains(event.target)) {
                cartDropdown.classList.add("hidden");
            }
        });
    });
</script>
