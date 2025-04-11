<?php
require_once dirname(__DIR__, 2) . '/Config/config.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';

$database = new Database();
$conn = $database->connect();

// Récupérer les filtres
$filters = [];
$result = $conn->query("SELECT DISTINCT lieu FROM Offre_Stage");
while ($row = $result->fetch_assoc()) {
    $filters['lieux'][] = $row['lieu'];
}
$result = $conn->query("SELECT DISTINCT duree FROM Offre_Stage");
while ($row = $result->fetch_assoc()) {
    $filters['durees'][] = $row['duree'];
}
$result = $conn->query("SELECT DISTINCT secteur_activite FROM Offre_Stage");
while ($row = $result->fetch_assoc()) {
    $filters['profils'][] = $row['secteur_activite'];
}

// Récupérer les offres de stage
$search = $_GET['search'] ?? '';
$lieu = $_GET['lieu'] ?? '';
$duree = $_GET['duree'] ?? '';
$profil = $_GET['profil'] ?? '';

$sql = "SELECT * FROM Offre_Stage WHERE (titre LIKE ? OR secteur_activite LIKE ?)";
$params = ["%$search%", "%$search%"];
$types = "ss";

if (!empty($lieu)) {
    $sql .= " AND lieu = ?";
    $params[] = $lieu;
    $types .= "s";
}
if (!empty($duree)) {
    $sql .= " AND duree = ?";
    $params[] = $duree;
    $types .= "i";
}
if (!empty($profil)) {
    $sql .= " AND secteur_activite = ?";
    $params[] = $profil;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$offres = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stages - WEB4ALL</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/stage.css">
</head>
<body>
    <header>
        <div class="logo">WEB4ALL</div>
        <nav>
            <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">Accueil</a>
        </nav>
    </header>

    <main>
        <section class="search-filters">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Rechercher un stage..." value="<?php echo htmlspecialchars($search); ?>">
                <select name="lieu">
                    <option value="">Lieu</option>
                    <?php foreach ($filters['lieux'] as $lieuOption): ?>
                        <option value="<?php echo htmlspecialchars($lieuOption); ?>" <?php echo $lieu === $lieuOption ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lieuOption); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="duree">
                    <option value="">Durée</option>
                    <?php foreach ($filters['durees'] as $dureeOption): ?>
                        <option value="<?php echo htmlspecialchars($dureeOption); ?>" <?php echo $duree == $dureeOption ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dureeOption); ?> mois
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="profil">
                    <option value="">Profil demandé</option>
                    <?php foreach ($filters['profils'] as $profilOption): ?>
                        <option value="<?php echo htmlspecialchars($profilOption); ?>" <?php echo $profil === $profilOption ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($profilOption); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="offers">
            <h2>Nos Offres de Stage</h2>
            <div class="offers-list">
                <?php if (!empty($offres)): ?>
                    <?php foreach ($offres as $offre): ?>
                        <div class="offer">
                            <h3><?php echo htmlspecialchars($offre['titre']); ?></h3>
                            <p><?php echo htmlspecialchars($offre['description']); ?></p>
                            <p><strong>Secteur :</strong> <?php echo htmlspecialchars($offre['secteur_activite']); ?></p>
                            <p><strong>Date de début :</strong> <?php echo htmlspecialchars($offre['date_debut']); ?></p>
                            <p><strong>Durée :</strong> <?php echo htmlspecialchars($offre['duree']); ?> mois</p>
                            <p><strong>Lieu :</strong> <?php echo htmlspecialchars($offre['lieu']); ?></p>
                            <p>
                                <button onclick="window.location.href='/projetWEB/MODEL-MVC/Views/stage/postuler.php?id=<?php echo htmlspecialchars((int)$offre['id_offre']); ?>'">
                                    Postuler
                                </button>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune offre de stage disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>