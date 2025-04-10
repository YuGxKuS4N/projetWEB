<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stagiaire') {
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

$stageId = $_GET['id'] ?? null;
if (!$stageId) {
    die("ID du stage manquant.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler - WEB4ALL</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/postuler.css">
</head>
<body>
    <div class="container">
        <h2 id="stage-title">Postuler pour le stage</h2>
        <form action="/projetWEB/MODEL-MVC/Controllers/c_candidature.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="stage_id" value="<?php echo htmlspecialchars($stageId); ?>">
            <div class="form-group">
                <label for="cv">Téléverser votre CV (PDF uniquement) :</label>
                <input type="file" id="cv" name="cv" accept=".pdf" required>
            </div>
            <div class="form-group">
                <label for="motivation">Téléverser votre lettre de motivation (PDF uniquement) :</label>
                <input type="file" id="motivation" name="motivation" accept=".pdf">
            </div>
            <button type="submit" class="upload-btn">Envoyer ma candidature</button>
        </form>
    </div>
</body>
</html>
