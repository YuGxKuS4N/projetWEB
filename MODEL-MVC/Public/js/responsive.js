document.addEventListener("DOMContentLoaded", () => {
    // Ajouter dynamiquement les styles CSS pour la navbar et le responsive design
    const style = document.createElement("style");
    style.textContent = `
        /* Styles de base pour la navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
        }

        .nav-logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar-menu {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .navbar-menu li {
            margin: 0 10px;
        }

        .navbar-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
        }

        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
                flex-direction: column;
                background-color: #444;
                position: absolute;
                top: 50px;
                right: 20px;
                width: 200px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .navbar-menu.active {
                display: flex;
            }

            .navbar-menu li {
                margin: 10px 0;
            }

            .navbar-toggle {
                display: block;
            }
        }
    `;
    document.head.appendChild(style);

    // Gestion de la navbar responsive
    const navbar = document.querySelector(".navbar");
    const navbarMenu = document.querySelector(".navbar-menu");

    // Fonction pour gérer le bouton toggle dynamiquement
    const handleResize = () => {
        const screenWidth = window.innerWidth;

        // Si l'écran est inférieur ou égal à 768px
        if (screenWidth <= 768) {
            // Ajouter le bouton toggle si non présent
            if (!document.getElementById("navbar-toggle")) {
                const navbarToggle = document.createElement("button");
                navbarToggle.id = "navbar-toggle";
                navbarToggle.className = "navbar-toggle";
                navbarToggle.innerHTML = `<span class="dots">•••</span>`;
                navbar.appendChild(navbarToggle);

                // Ajouter l'événement pour afficher/masquer la navbar
                navbarToggle.addEventListener("click", () => {
                    navbarMenu.classList.toggle("active");
                });
            }
        } else {
            // Supprimer le bouton toggle si présent
            const navbarToggle = document.getElementById("navbar-toggle");
            if (navbarToggle) {
                navbarToggle.remove();
                navbarMenu.classList.remove("active"); // Réinitialiser l'état
            }
        }
    };

    // Appeler la fonction au chargement et à chaque redimensionnement
    handleResize();
    window.addEventListener("resize", handleResize);
});