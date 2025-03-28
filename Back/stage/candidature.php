<?php
// Vérification du fichier téléchargé
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['cv'])) {
    $errors = [];
    
    // Taille maximale du fichier (2 Mo)
    $maxSize = 2 * 1024 * 1024; // 2 Mo en octets
    
    // Extensions autorisées
    $allowedExtensions = ['pdf'];
    
    // Vérifier la taille du fichier
    if ($_FILES['cv']['size'] > $maxSize) {
        $errors[] = "Le fichier est trop volumineux. La taille maximale est de 2 Mo.";
    }
    
    // Vérifier l'extension du fichier
    $fileInfo = pathinfo($_FILES['cv']['name']);
    $fileExtension = strtolower($fileInfo['extension']);
    if (!in_array($fileExtension, $allowedExtensions)) {
        $errors[] = "Seuls les fichiers PDF sont autorisés.";
    }
    
    // Vérifier le type MIME du fichier
    $mimeType = mime_content_type($_FILES['cv']['tmp_name']);
    if ($mimeType != 'application/pdf') {
        $errors[] = "Le fichier doit être au format PDF.";
    }
    
    // Si aucune erreur, procéder au téléversement
    if (empty($errors)) {
        $uploadDirectory = 'uploads/';
        $uploadFile = $uploadDirectory . basename($_FILES['cv']['name']);
        
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $uploadFile)) {
            echo "Le fichier a été téléchargé avec succès.";
        } else {
            echo "Une erreur est survenue lors du téléchargement du fichier.";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>

<!-- Formulaire de téléchargement de CV -->
<form action="" method="post" enctype="multipart/form-data">
    <label for="cv">Téléverser un CV (PDF uniquement):</label>
    <input type="file" name="cv" id="cv" required>
    <button type="submit">Envoyer</button>
</form>
