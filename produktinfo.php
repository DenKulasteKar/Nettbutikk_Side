<?php
require "db_connect.php";
require "navbar.php";

// Sjekk om produkt_id er satt i URL
if (!isset($_GET['produkt_id'])) {
    die("Ugyldig produkt.");
}

$produkt_id = intval($_GET['produkt_id']);

// Hent produktinformasjon fra databasen
$sql = "SELECT namn, beskriving, pris, lagerkvantitet, bilde FROM produkt WHERE produkt_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $produkt_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Produktet finnes ikke.");
}
$produkt = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produkt['namn']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-100">
    <div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
        <h1 class="text-2xl font-bold text-indigo-900 mb-4"><?php echo htmlspecialchars($produkt['namn']); ?></h1>
        <img src="../<?php echo htmlspecialchars($produkt['bilde']); ?>" alt="<?php echo htmlspecialchars($produkt['namn']); ?>" class="w-full h-60 object-cover rounded-md mb-4">
        <p class="text-gray-700 mb-2"><?php echo nl2br(htmlspecialchars($produkt['beskriving'])); ?></p>
        <p class="text-indigo-900 font-bold text-lg mb-2">Pris: <?php echo number_format($produkt['pris'], 2); ?> kr</p>
        <p class="text-gray-600">Lager: <?php echo $produkt['lagerkvantitet']; ?></p>
        <button class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Legg til i handlekurv</button>
    </div>
</body>
</html>

<?php
$conn->close();
?>