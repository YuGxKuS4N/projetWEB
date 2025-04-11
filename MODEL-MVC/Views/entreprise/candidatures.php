<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

require_once dirname(__DIR__, 2) . '/Config/Database.php';
require_once dirname(__DIR__, 2) . '/Controllers/c_candidature.php';

$database = new Database();
$candidatureController = new CandidatureController($database);
$candidatures = $candidatureController->getCandidaturesByEntreprise($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatures reçues</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/candidatures.css">
</head>
<body>
<nav class="navbar">
        <div class="nav-logo">
            <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">
                <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
            </a>
        </div>

        <uel class="nav-right">
            <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>
        </ul>
    </nav>
    <div class="container">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p><a href="/projetWEB/MODEL-MVC/Views/creation_compte/deconnexion.php">Déconnexion</a></p>
    <div class="container">
        <h2>Candidatures reçues</h2>
        <?php if (!empty($candidatures)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom du stagiaire</th>
                        <th>Offre de stage</th>
                        <th>CV</th>
                        <th>Lettre de motivation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candidatures as $candidature): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']); ?></td>
                            <td><?php echo htmlspecialchars($candidature['titre']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($candidature['cv_path']); ?>" target="_blank">Télécharger</a></td>
                            <td>
                                <?php if (!empty($candidature['motivation_path'])): ?>
                                    <a href="<?php echo htmlspecialchars($candidature['motivation_path']); ?>" target="_blank">Télécharger</a>
                                <?php else: ?>
                                    Non fourni
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune candidature reçue pour le moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
