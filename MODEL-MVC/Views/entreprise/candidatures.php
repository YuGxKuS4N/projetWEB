<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

require_once __DIR__ . '/../../Controllers/c_candidature.php';

$entrepriseId = $_SESSION['user_id'];
$candidatureController = new CandidatureController();
$candidatures = $candidatureController->getCandidaturesByEntreprise($entrepriseId);
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
    <div class="container">
        <h2>Candidatures reçues</h2>
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
                <?php if (!empty($candidatures)): ?>
                    <?php foreach ($candidatures as $candidature): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($candidature['stagiaire_prenom'] . ' ' . $candidature['stagiaire_nom']); ?></td>
                            <td><?php echo htmlspecialchars($candidature['offre_titre']); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($candidature['cv_path']); ?>" target="_blank">Télécharger</a>
                            </td>
                            <td>
                                <a href="<?php echo htmlspecialchars($candidature['motivation_path']); ?>" target="_blank">Télécharger</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune candidature reçue pour le moment.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>