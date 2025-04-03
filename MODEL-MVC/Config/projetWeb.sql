-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 02, 2025 at 10:45 AM
-- Server version: 8.0.41-0ubuntu0.24.10.1
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projet`
--

-- --------------------------------------------------------

--
-- Table structure for table `Administrateur`
--

CREATE TABLE `Administrateur` (
  `id_admin` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Administrateur`
--

INSERT INTO `Administrateur` (`id_admin`, `nom`, `prenom`, `email`, `telephone`, `role`) VALUES
(1, 'Dupont', 'Pierre', 'pierre.dupont@ecole.fr', '0123456789', 'Administrateur principal');

-- --------------------------------------------------------

--
-- Table structure for table `Admin_Logs`
--

CREATE TABLE `Admin_Logs` (
  `id_log` int NOT NULL,
  `id_admin_fk` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `table_concernee` varchar(50) NOT NULL,
  `id_element` int DEFAULT NULL,
  `date_action` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Candidature`
--

CREATE TABLE `Candidature` (
  `id_candidature` int NOT NULL,
  `id_etudiant_fk` int NOT NULL,
  `id_offre_fk` int NOT NULL,
  `id_cv_fk` int DEFAULT NULL,
  `id_lettre_fk` int DEFAULT NULL,
  `date_candidature` date NOT NULL,
  `statut_candidature` enum('En attente','En cours de traitement','Entretien planifié','Acceptée','Refusée') DEFAULT 'En attente',
  `commentaire` text,
  `id_entreprise_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `CV`
--

CREATE TABLE `CV` (
  `id_cv` int NOT NULL,
  `id_etudiant_fk` int NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `type_fichier` varchar(50) NOT NULL,
  `taille_fichier` int NOT NULL,
  `date_upload` datetime NOT NULL,
  `contenu` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Entreprise`
--

CREATE TABLE `Entreprise` (
  `id_entreprise` int NOT NULL,
  `nom_entreprise` varchar(100) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `secteur` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Entreprise`
--

INSERT INTO `Entreprise` (`id_entreprise`, `nom_entreprise`, `adresse`, `secteur`, `contact_person`) VALUES
(1, 'TechSolutions', '15 rue des Innovations, Paris', 'Informatique', 'Marie Dubois');

-- --------------------------------------------------------

--
-- Table structure for table `Etudiant`
--

CREATE TABLE `Etudiant` (
  `id_etudiant` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Lettre_Motivation`
--

CREATE TABLE `Lettre_Motivation` (
  `id_lettre` int NOT NULL,
  `id_etudiant_fk` int NOT NULL,
  `titre` varchar(255) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `type_fichier` varchar(50) NOT NULL,
  `taille_fichier` int NOT NULL,
  `date_upload` datetime NOT NULL,
  `contenu` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Offre_Stage`
--

CREATE TABLE `Offre_Stage` (
  `id_offre` int NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date_publi` date NOT NULL,
  `date_debut` date NOT NULL,
  `duree` int NOT NULL,
  `lieu_stage` varchar(255) NOT NULL,
  `id_entreprise_fk` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Pilote`
--

CREATE TABLE `Pilote` (
  `id_pilote` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `id_etudiant_fk` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Pilote`
--

INSERT INTO `Pilote` (`id_pilote`, `nom`, `prenom`, `email`, `telephone`, `id_etudiant_fk`) VALUES
(1, 'Leblanc', 'Jean', 'jean.leblanc@ecole.fr', '0234567890', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Wishlist`
--

CREATE TABLE `Wishlist` (
  `id_wishlist` int NOT NULL,
  `id_etudiant_fk` int NOT NULL,
  `id_offre_fk` int NOT NULL,
  `date_ajout` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Administrateur`
--
ALTER TABLE `Administrateur`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Admin_Logs`
--
ALTER TABLE `Admin_Logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_admin_fk` (`id_admin_fk`);

--
-- Indexes for table `Candidature`
--
ALTER TABLE `Candidature`
  ADD PRIMARY KEY (`id_candidature`),
  ADD KEY `id_etudiant_fk` (`id_etudiant_fk`),
  ADD KEY `id_offre_fk` (`id_offre_fk`),
  ADD KEY `id_cv_fk` (`id_cv_fk`),
  ADD KEY `id_lettre_fk` (`id_lettre_fk`),
  ADD KEY `id_entreprise_fk` (`id_entreprise_fk`);

--
-- Indexes for table `CV`
--
ALTER TABLE `CV`
  ADD PRIMARY KEY (`id_cv`),
  ADD KEY `id_etudiant_fk` (`id_etudiant_fk`);

--
-- Indexes for table `Entreprise`
--
ALTER TABLE `Entreprise`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Indexes for table `Etudiant`
--
ALTER TABLE `Etudiant`
  ADD PRIMARY KEY (`id_etudiant`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Lettre_Motivation`
--
ALTER TABLE `Lettre_Motivation`
  ADD PRIMARY KEY (`id_lettre`),
  ADD KEY `id_etudiant_fk` (`id_etudiant_fk`);

--
-- Indexes for table `Offre_Stage`
--
ALTER TABLE `Offre_Stage`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `id_entreprise_fk` (`id_entreprise_fk`);

--
-- Indexes for table `Pilote`
--
ALTER TABLE `Pilote`
  ADD PRIMARY KEY (`id_pilote`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_etudiant_fk` (`id_etudiant_fk`);

--
-- Indexes for table `Wishlist`
--
ALTER TABLE `Wishlist`
  ADD PRIMARY KEY (`id_wishlist`),
  ADD UNIQUE KEY `unique_wishlist` (`id_etudiant_fk`,`id_offre_fk`),
  ADD KEY `id_offre_fk` (`id_offre_fk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Administrateur`
--
ALTER TABLE `Administrateur`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Admin_Logs`
--
ALTER TABLE `Admin_Logs`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Candidature`
--
ALTER TABLE `Candidature`
  MODIFY `id_candidature` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `CV`
--
ALTER TABLE `CV`
  MODIFY `id_cv` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Entreprise`
--
ALTER TABLE `Entreprise`
  MODIFY `id_entreprise` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Etudiant`
--
ALTER TABLE `Etudiant`
  MODIFY `id_etudiant` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Lettre_Motivation`
--
ALTER TABLE `Lettre_Motivation`
  MODIFY `id_lettre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Offre_Stage`
--
ALTER TABLE `Offre_Stage`
  MODIFY `id_offre` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Pilote`
--
ALTER TABLE `Pilote`
  MODIFY `id_pilote` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Wishlist`
--
ALTER TABLE `Wishlist`
  MODIFY `id_wishlist` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Admin_Logs`
--
ALTER TABLE `Admin_Logs`
  ADD CONSTRAINT `Admin_Logs_ibfk_1` FOREIGN KEY (`id_admin_fk`) REFERENCES `Administrateur` (`id_admin`);

--
-- Constraints for table `Candidature`
--
ALTER TABLE `Candidature`
  ADD CONSTRAINT `Candidature_ibfk_1` FOREIGN KEY (`id_etudiant_fk`) REFERENCES `Etudiant` (`id_etudiant`) ON DELETE CASCADE,
  ADD CONSTRAINT `Candidature_ibfk_2` FOREIGN KEY (`id_offre_fk`) REFERENCES `Offre_Stage` (`id_offre`) ON DELETE CASCADE,
  ADD CONSTRAINT `Candidature_ibfk_3` FOREIGN KEY (`id_cv_fk`) REFERENCES `CV` (`id_cv`) ON DELETE SET NULL,
  ADD CONSTRAINT `Candidature_ibfk_4` FOREIGN KEY (`id_lettre_fk`) REFERENCES `Lettre_Motivation` (`id_lettre`) ON DELETE SET NULL,
  ADD CONSTRAINT `Candidature_ibfk_5` FOREIGN KEY (`id_entreprise_fk`) REFERENCES `Entreprise` (`id_entreprise`) ON DELETE CASCADE;

--
-- Constraints for table `CV`
--
ALTER TABLE `CV`
  ADD CONSTRAINT `CV_ibfk_1` FOREIGN KEY (`id_etudiant_fk`) REFERENCES `Etudiant` (`id_etudiant`) ON DELETE CASCADE;

--
-- Constraints for table `Lettre_Motivation`
--
ALTER TABLE `Lettre_Motivation`
  ADD CONSTRAINT `Lettre_Motivation_ibfk_1` FOREIGN KEY (`id_etudiant_fk`) REFERENCES `Etudiant` (`id_etudiant`) ON DELETE CASCADE;

--
-- Constraints for table `Offre_Stage`
--
ALTER TABLE `Offre_Stage`
  ADD CONSTRAINT `Offre_Stage_ibfk_1` FOREIGN KEY (`id_entreprise_fk`) REFERENCES `Entreprise` (`id_entreprise`) ON DELETE CASCADE;

--
-- Constraints for table `Pilote`
--
ALTER TABLE `Pilote`
  ADD CONSTRAINT `Pilote_ibfk_1` FOREIGN KEY (`id_etudiant_fk`) REFERENCES `Etudiant` (`id_etudiant`) ON DELETE SET NULL;

--
-- Constraints for table `Wishlist`
--
ALTER TABLE `Wishlist`
  ADD CONSTRAINT `Wishlist_ibfk_1` FOREIGN KEY (`id_etudiant_fk`) REFERENCES `Etudiant` (`id_etudiant`) ON DELETE CASCADE,
  ADD CONSTRAINT `Wishlist_ibfk_2` FOREIGN KEY (`id_offre_fk`) REFERENCES `Offre_Stage` (`id_offre`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
