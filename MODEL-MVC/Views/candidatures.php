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
                <?php foreach ($candidatures as $candidature): ?>
                    <tr>
                        <td><?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?></td>
                        <td><?= htmlspecialchars($candidature['titre']) ?></td>
                        <td>
                            <a href="<?= htmlspecialchars($candidature['cv_path']) ?>" target="_blank">Télécharger</a>
                        </td>
                        <td>
                            <?php if ($candidature['motivation_path']): ?>
                                <a href="<?= htmlspecialchars($candidature['motivation_path']) ?>" target="_blank">Télécharger</a>
                            <?php else: ?>
                                Non fourni
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
