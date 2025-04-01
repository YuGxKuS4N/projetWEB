<?php
require_once '../../Controllers/c_inscription.php'; // Inclusion du contrôleur pour gérer l'inscription
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription - WEB4ALL</title>
  <link rel="stylesheet" href="../../../Public/css/inscription.css" />
  <script src="../../../Public/js/inscription.js" defer></script> <!-- Appel du fichier JS -->
</head>
<body>
  <nav class="navbar">
    <ul class="nav-left">
      <li><a href="../acceuil/acceuil.php">ACCUEIL</a></li>
      <li><a href="../stage/stage.php">STAGE</a></li>
    </ul>
    <div class="nav-logo">
      <a href="../acceuil/acceuil.php">
        <img src="../../../Public/images/logo.png" alt="Logo du Site" />
      </a>
    </div>
    <ul class="nav-right">
      <li><a href="connexion.php">CONNEXION</a></li>
    </ul>
  </nav>

  <div class="form-box">
    <div class="tabs">
      <button onclick="showTab('stagiaire', event)" class="tab-button active">Espace Stagiaire</button>
      <button onclick="showTab('entreprise', event)" class="tab-button">Espace Entreprise</button>
      <button onclick="showTab('pilote', event)" class="tab-button">Espace Pilote</button>
    </div>

    <!-- Formulaire Stagiaire -->
    <div id="stagiaire" class="tab-content" style="display: block;">
      <form class="form" action="../../../Controllers/c_inscription.php" method="POST">
        <span class="title">Créer un compte Stagiaire</span>
        <div class="form-container">
          <input type="text" class="input" name="prenom" placeholder="Prénom" required />
          <input type="text" class="input" name="nom" placeholder="Nom" required />
          <input type="email" class="input" name="email" placeholder="E-mail" required />
          <input type="password" class="input" name="password" placeholder="Mot de passe" required />
          <input type="password" class="input" name="confirm_password" placeholder="Confirmez le mot de passe" required />
          <label for="secteur">Secteur d'activité recherché</label>
          <select id="secteur" name="secteur" required>
            <option value="">Sélectionnez un secteur</option>
            <option value="Développement Web">Développement Web</option>
            <option value="Développement Mobile">Développement Mobile</option>
            <option value="Intelligence Artificielle">Intelligence Artificielle</option>
            <option value="Cybersécurité">Cybersécurité</option>
            <option value="Big Data">Big Data</option>
            <option value="Réseaux et Télécommunications">Réseaux et Télécommunications</option>
            <option value="Cloud Computing">Cloud Computing</option>
            <option value="Internet des Objets (IoT)">Internet des Objets (IoT)</option>
            <option value="Réalité Virtuelle et Augmentée">Réalité Virtuelle et Augmentée</option>
            <option value="Gestion de Projets Informatiques">Gestion de Projets Informatiques</option>
          </select>
        </div>
        <button type="submit" name="type" value="stagiaire">S'inscrire</button>
      </form>
    </div>

    <!-- Formulaire Entreprise -->
    <div id="entreprise" class="tab-content" style="display: none;">
      <form class="form" action="../../../Controllers/c_inscription.php" method="POST">
        <span class="title">Créer un compte Entreprise</span>
        <div class="form-container">
          <input type="text" class="input" name="nom_entreprise" placeholder="Nom de l'entreprise" required />
          <input type="text" class="input" name="prenom" placeholder="Prénom du contact" required />
          <input type="text" class="input" name="nom" placeholder="Nom du contact" required />
          <input type="tel" class="input" name="telephone" placeholder="Numéro de téléphone" pattern="[0-9]{10}" required />
          <input type="email" class="input" name="email" placeholder="E-mail" required />
          <input type="password" class="input" name="password" placeholder="Mot de passe" required />
          <input type="password" class="input" name="confirm_password" placeholder="Confirmez le mot de passe" required />
        </div>
        <button type="submit" name="type" value="entreprise">S'inscrire</button>
      </form>
    </div>

    <!-- Formulaire Pilote -->
    <div id="pilote" class="tab-content" style="display: none;">
      <form class="form" action="../../../Controllers/c_inscription.php" method="POST">
        <span class="title">Créer un compte Pilote</span>
        <div class="form-container">
          <input type="text" class="input" name="prenom" placeholder="Prénom" required />
          <input type="text" class="input" name="nom" placeholder="Nom" required />
          <input type="text" class="input" name="ecole" placeholder="École" required />
          <input type="text" class="input" name="lieu_ecole" placeholder="Lieu de l'école" required />
          <input type="number" class="input" name="annee_promo" placeholder="Année de promotion" required />
          <input type="tel" class="input" name="telephone" placeholder="Numéro de téléphone" pattern="[0-9]{10}" required />
          <input type="email" class="input" name="email" placeholder="E-mail" required />
          <input type="password" class="input" name="password" placeholder="Mot de passe" required />
          <input type="password" class="input" name="confirm_password" placeholder="Confirmez le mot de passe" required />
        </div>
        <button type="submit" name="type" value="pilote">S'inscrire</button>
      </form>
    </div>
  </div>
</body>
</html>