<?php

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../acceuil/acceuil.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - WEB4ALL</title>
    <link rel="stylesheet" href="../../../Public/css/admin.css">
</head>
<body>
    <header>
        <h1>Interface d'administration</h1>
    </header>
    <main>
        <section>
            <h2>Gérer les comptes</h2>
            <button onclick="manageAccounts()">Voir les comptes</button>
        </section>
        <section>
            <h2>Gérer les stages</h2>
            <button onclick="manageStages()">Voir les stages</button>
        </section>
    </main>
    <script src="../../../Public/js/admin.js"></script>
</body>
</html>