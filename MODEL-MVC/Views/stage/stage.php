<?php
require_once dirname(__DIR__, 2) . '/Config/config.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';

session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stagiaire') {
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

// Connexion à la base de données
$database = new Database();
$conn = $database->connect();

// Récupérer l'utilisateur connecté
$idStagiaire = $_SESSION['user_id'];

// Gestion des actions pour la wishlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['stage_id'])) {
    $stageId = intval($_POST['stage_id']);
    $action = $_POST['action'];

    if ($action === 'add') {
        // Ajouter à la wishlist
        $sqlCheck = "SELECT * FROM wishlist WHERE id_stagiaire = ? AND id_stage = ?";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $idStagiaire, $stageId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $sqlInsert = "INSERT INTO wishlist (id_stagiaire, id_stage) VALUES (?, ?)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bind_param("ii", $idStagiaire, $stageId);
            $stmt->execute();
        }
    } elseif ($action === 'remove') {
        // Retirer de la wishlist
        $sqlDelete = "DELETE FROM wishlist WHERE id_stagiaire = ? AND id_stage = ?";
        $stmt = $conn->prepare($sqlDelete);
        $stmt->bind_param("ii", $idStagiaire, $stageId);
        $stmt->execute();
    }
}

// Récupérer les filtres de recherche
$search = $_GET['search'] ?? '';
$lieu = $_GET['lieu'] ?? '';
$duree = $_GET['duree'] ?? '';
$profil = $_GET['profil'] ?? '';

// Construire la requête SQL pour récupérer les offres de stage
$sql = "SELECT `stage-id`, titre, description, duree, lieu, date_debut, secteur_activite FROM Offre_Stage WHERE (titre LIKE ? OR secteur_activite LIKE ?)";
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

// Préparer et exécuter la requête
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Récupérer les résultats
$offres = [];
while ($row = $result->fetch_assoc()) {
    $offres[] = $row;
}

// Récupérer les stages dans la wishlist
$wishlist = [];
$sqlWishlist = "SELECT id_stage FROM wishlist WHERE id_stagiaire = ?";
$stmt = $conn->prepare($sqlWishlist);
$stmt->bind_param("i", $idStagiaire);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $wishlist[] = $row['id_stage'];
}

// Récupérer les filtres disponibles
$filters = [
    'lieux' => [],
    'durees' => [],
    'profils' => []
];

// Récupérer les lieux
$result = $conn->query("SELECT DISTINCT lieu FROM Offre_Stage");
while ($row = $result->fetch_assoc()) {
    $filters['lieux'][] = $row['lieu'];
}

// Récupérer les durées
$result = $conn->query("SELECT DISTINCT duree FROM Offre_Stage");
while ($row = $result->fetch_assoc()) {
    $filters['durees'][] = $row['duree'];
}

// Récupérer les profils
$result = $conn->query("SELECT DISTINCT secteur_activite FROM Offre_Stage");
while ($row = $result->fetch_assoc()) {
    $filters['profils'][] = $row['secteur_activite'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stages - WEB4ALL</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/stage.css">
    <style>
        .wishlist-star {
            font-size: 24px;
            color: gray;
            cursor: pointer;
        }
        .wishlist-star.active {
            color: gold;
        }
    </style>
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
                            <form method="POST" action="">
                                <!-- Étoile pour la wishlist -->
                                <button type="submit" name="action" value="<?php echo in_array($offre['stage-id'], $wishlist) ? 'remove' : 'add'; ?>" class="wishlist-star <?php echo in_array($offre['stage-id'], $wishlist) ? 'active' : ''; ?>">
                                    ★
                                </button>
                                <input type="hidden" name="stage_id" value="<?php echo htmlspecialchars($offre['stage-id']); ?>">
                            </form>
                            <h3><?php echo htmlspecialchars($offre['titre']); ?></h3>
                            <p><?php echo htmlspecialchars($offre['description']); ?></p>
                            <p><strong>Secteur :</strong> <?php echo htmlspecialchars($offre['secteur_activite']); ?></p>
                            <p><strong>Date de début :</strong> <?php echo htmlspecialchars($offre['date_debut']); ?></p>
                            <p><strong>Durée :</strong> <?php echo htmlspecialchars($offre['duree']); ?> mois</p>
                            <p><strong>Lieu :</strong> <?php echo htmlspecialchars($offre['lieu']); ?></p>
                            <p>
                                <button onclick="window.location.href='/projetWEB/MODEL-MVC/Views/stage/postuler.php?id=<?php echo htmlspecialchars($offre['stage-id']); ?>'">
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