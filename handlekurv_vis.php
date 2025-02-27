<?php require "navbar.php"; ?>
<?php require "db_connect.php"; ?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hent handlekurvdata fra cookies
$handlekurv = isset($_COOKIE['handlekurv']) ? json_decode($_COOKIE['handlekurv'], true) : [];

// Hent produktdetaljer fra databasen
$produkt_data = [];
if (!empty($handlekurv)) {
    $produkt_ids = array_keys($handlekurv);
    $placeholders = implode(',', array_fill(0, count($produkt_ids), '?'));

    $sql = "SELECT produkt_id, namn, pris FROM produkt WHERE produkt_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($produkt_ids)), ...$produkt_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $produkt_data[$row['produkt_id']] = $row;
    }
    $stmt->close();
}

// Oppdater lageret i databasen
$sql = "UPDATE produkt SET lagerkvantitet = lagerkvantitet - 1 WHERE produkt_id = ? AND lagerkvantitet > 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $produkt_id);
$stmt->execute();


?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handlekurv</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-100 p-10">

    <div class="container mx-auto bg-white p-6 shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Handlekurv</h1>

        <?php if (empty($produkt_data)): ?>
            <p>Handlekurven er tom.</p>
        <?php else: ?>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Produkt</th>
                        <th class="border border-gray-300 px-4 py-2">Antall</th>
                        <th class="border border-gray-300 px-4 py-2">Pris</th>
                        <th class="border border-gray-300 px-4 py-2">Total</th>
                        <th class="border border-gray-300 px-4 py-2">Handling</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($handlekurv as $id => $produkt): ?>
                        <?php if (!isset($produkt_data[$id])) continue; ?>
                        <?php 
                            $produktinfo = $produkt_data[$id];
                            $antall = $produkt['antall'];
                            $subtotal = $produktinfo['pris'] * $antall;
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($produktinfo['namn']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $antall; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo number_format($produktinfo['pris'], 2); ?> kr</td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo number_format($subtotal, 2); ?> kr</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a href="handlekurv_fjern.php?produkt_id=<?php echo $id; ?>" class="text-red-600">Fjern</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="mt-4 text-lg font-bold">Total: <?php echo number_format($total, 2); ?> kr</p>
        <?php endif; ?>

        <a href="kasse.php" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">Gå til kassen</a>
    </div>

</body>
</html>
