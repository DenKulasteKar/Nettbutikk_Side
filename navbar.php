<?php require "db_connect.php"; ?>
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    session_start();

    $brukernamn = "Gjest"; // Default name for guest users

    if (isset($_SESSION['brukar_id'])) {
        $brukar_id = $_SESSION['brukar_id'];
        $stmt = $conn->prepare("SELECT brukarnamn FROM brukar WHERE brukar_id = ?");
        $stmt->bind_param("i", $brukar_id);
        $stmt->execute();
        $stmt->bind_result($brukernamn);
        $stmt->fetch();
        $stmt->close();
    }
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
    <!-- Navbar -->
    <nav class="bg-indigo-600 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="heimside.php" class="text-white text-lg font-bold">MinSide</a>
        <button id="menu-btn" class="text-white lg:hidden">☰</button>
        <div class="hidden lg:flex space-x-4 items-center" id="menu">
           
            <?php require "handlekurv.php"; ?>
            <?php if (isset($_SESSION['brukar_id'])): ?>
                <div class="relative group">
                    <button class="text-white px-4 hover:bg-indigo-700 rounded"><?php echo htmlspecialchars($brukernamn); ?> ▼</button>
                    <div class="absolute hidden bg-white text-indigo-600 shadow-md rounded group-hover:block">
                        <a href="ordre.php" class="block px-4 py-2 hover:bg-indigo-100">Mine ordre</a>
                        <a href="loggut.php" class="block px-4 py-2 hover:bg-indigo-100">Logg ut</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="text-white px-4 hover:bg-indigo-700 rounded">Logg inn</a>
                <a href="register.php" class="text-white px-4 hover:bg-indigo-700 rounded">Registrer deg</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

</body>
</html>
