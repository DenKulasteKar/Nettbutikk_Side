<?php
require "navbar.php";
require "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brukarnamn = trim($_POST['brukarnamn']);
    $passord = password_hash($_POST['passord'], PASSWORD_DEFAULT);
    $epost = trim($_POST['epost']);
    $fornamn = trim($_POST['fornamn']);
    $etternamn = trim($_POST['etternamn']);
    $adresse = trim($_POST['adresse']);

    // Sjekk om brukernavnet allerede finnes
    $stmt = $conn->prepare("SELECT * FROM brukar WHERE brukarnamn = ?");
    $stmt->bind_param("s", $brukarnamn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Brukernavnet er allerede tatt.";
    } else {
        // Sett inn ny bruker
        $sql = "INSERT INTO brukar (brukarnamn, passord, epost, fornamn, etternamn, adresse) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $brukarnamn, $passord, $epost, $fornamn, $etternamn, $adresse);

        if ($stmt->execute()) {
            $success = "Brukeren ble registrert!";
        } else {
            $error = "Feil ved registrering: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrer Bruker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-indigo-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-center text-indigo-700 mb-6">Registrer Bruker</h2>

        <!-- Viser meldinger -->
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?= $error ?></p>
        <?php elseif (isset($success)): ?>
            <p class="text-green-500 text-center mb-4"><?= $success ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-4">
                <label class="block text-gray-700">Brukernavn</label>
                <input type="text" name="brukarnamn" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Passord</label>
                <input type="password" name="passord" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">E-post</label>
                <input type="email" name="epost" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Fornavn</label>
                <input type="text" name="fornamn" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Etternavn</label>
                <input type="text" name="etternamn" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700">Adresse</label>
                <input type="text" name="adresse" required class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700">Registrer</button>
        </form>
    </div>
</body>
</html>
