<?php  
session_start(); // Démarre la session PHP  
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>WEB4ALL</title>  
    <style>
        /* Style général */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        header {
            width: 100%;
            background: #fff;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            height: 80px;
        }

        .nav-left, .nav-right {
            list-style: none;
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .nav-left li, .nav-right li {
            margin: 0 10px;
        }

        .nav-left a, .nav-right a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
            pointer-events: none; /* Désactive le clic */
        }

        .nav-logo img {
            height: 60px;
            width: auto;
            pointer-events: none; /* Désactive le clic sur l'image */
        }

        .welcome-container {
            text-align: center;
            margin-top: 150px;
        }

        .welcome-container img {
            max-width: 300px;
            margin-bottom: 20px;
        }

        .welcome-container h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-container p {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 20px;
        }

        .welcome-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .welcome-container a:hover {
            background-color: #0056b3;
        }

        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>  
<body>  
    <header>  
        <nav class="navbar">
            <ul class="nav-left">
                <li><a href="#">ACCUEIL</a></li>
                <li><a href="#">STAGE</a></li>
            </ul>    

            <div class="nav-logo">
                <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
            </div>

            <ul class="nav-right">
                <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>
            </ul>
        </nav>
    </header>  

    <div class="welcome-container">
        <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo WEB4ALL">
        <h1>Bienvenue sur WEB4ALL</h1>
        <p>Veuillez vous connecter pour pouvoir accéder au site.</p>
        <a href="/projetWEB/MODEL-MVC/Views/connexion/connexion.php">Se connecter</a>
    </div>

    <footer>
        <p>&copy; 2025 WEB4ALL. Tous droits réservés.</p>
    </footer>
</body>  
</html>