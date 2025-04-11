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
    <script>
        // Fonction pour gérer l'ajout ou la suppression de la wishlist
        function toggleWishlist(stageId, action) {
            fetch('/projetWEB/MODEL-MVC/Controllers/wishlist.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ stageId: stageId, action: action })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau : ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    const star = document.getElementById(`wishlist-star-${stageId}`);
                    if (action === 'add') {
                        star.classList.add('active');
                        star.setAttribute('onclick', `toggleWishlist(${stageId}, 'remove')`);
                    } else {
                        star.classList.remove('active');
                        star.setAttribute('onclick', `toggleWishlist(${stageId}, 'add')`);
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erreur lors de la requête wishlist:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            });
        }
    </script>
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
                            <!-- Étoile pour la wishlist -->
                            <span 
                                id="wishlist-star-<?php echo htmlspecialchars($offre['stage-id']); ?>" 
                                class="wishlist-star" 
                                onclick="toggleWishlist(<?php echo htmlspecialchars($offre['stage-id']); ?>, 'add')">
                                ★
                            </span>
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