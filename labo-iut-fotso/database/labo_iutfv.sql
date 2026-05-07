--- labo-iut-fotso/database/labo_iutfv.sql (原始)


+++ labo-iut-fotso/database/labo_iutfv.sql (修改后)
-- =====================================================
-- Script SQL complet pour le Laboratoire de Recherche
-- IUT Fotso Victor de Bandjoun
-- =====================================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS labo_iutfv CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE labo_iutfv;

-- =====================================================
-- TABLE : utilisateurs
-- =====================================================
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin','editeur','chercheur') DEFAULT 'editeur',
    actif TINYINT(1) DEFAULT 1,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : axes_recherche
-- =====================================================
CREATE TABLE IF NOT EXISTS axes_recherche (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    icone VARCHAR(100),
    responsable_id INT,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : chercheurs
-- =====================================================
CREATE TABLE IF NOT EXISTS chercheurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    grade ENUM('Professeur','MCF','Doctorant','Ingénieur','Post-doc') NOT NULL,
    specialite VARCHAR(200),
    biographie TEXT,
    email VARCHAR(150),
    photo VARCHAR(255),
    axe_id INT,
    statut ENUM('actif','inactif') DEFAULT 'actif',
    date_integration DATE,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (axe_id) REFERENCES axes_recherche(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ajout FK responsable dans axes_recherche après création de chercheurs
ALTER TABLE axes_recherche ADD CONSTRAINT fk_responsable
    FOREIGN KEY (responsable_id) REFERENCES chercheurs(id) ON DELETE SET NULL;

-- =====================================================
-- TABLE : publications
-- =====================================================
CREATE TABLE IF NOT EXISTS publications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(300) NOT NULL,
    resume TEXT,
    annee YEAR NOT NULL,
    type ENUM('Article','Thèse','Conférence','Rapport') NOT NULL,
    fichier_pdf VARCHAR(255),
    lien_externe VARCHAR(500),
    mots_cles VARCHAR(300),
    axe_id INT,
    langue ENUM('FR','EN') DEFAULT 'FR',
    visible TINYINT(1) DEFAULT 1,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (axe_id) REFERENCES axes_recherche(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : publication_auteurs (table pivot)
-- =====================================================
CREATE TABLE IF NOT EXISTS publication_auteurs (
    publication_id INT NOT NULL,
    chercheur_id INT NOT NULL,
    PRIMARY KEY(publication_id, chercheur_id),
    FOREIGN KEY (publication_id) REFERENCES publications(id) ON DELETE CASCADE,
    FOREIGN KEY (chercheur_id) REFERENCES chercheurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : projets
-- =====================================================
CREATE TABLE IF NOT EXISTS projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(300) NOT NULL,
    description TEXT,
    objectifs TEXT,
    date_debut DATE,
    date_fin DATE,
    statut ENUM('En cours','Terminé','À venir') NOT NULL,
    financeur VARCHAR(200),
    axe_id INT,
    livrables TEXT,
    visible TINYINT(1) DEFAULT 1,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (axe_id) REFERENCES axes_recherche(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : projet_chercheurs (table pivot)
-- =====================================================
CREATE TABLE IF NOT EXISTS projet_chercheurs (
    projet_id INT NOT NULL,
    chercheur_id INT NOT NULL,
    PRIMARY KEY(projet_id, chercheur_id),
    FOREIGN KEY (projet_id) REFERENCES projets(id) ON DELETE CASCADE,
    FOREIGN KEY (chercheur_id) REFERENCES chercheurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : evenements
-- =====================================================
CREATE TABLE IF NOT EXISTS evenements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(300) NOT NULL,
    type ENUM('Séminaire','Atelier','Conférence','Soutenance') NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME,
    lieu VARCHAR(200),
    description TEXT,
    programme TEXT,
    inscription_requise TINYINT(1) DEFAULT 0,
    lien_inscription VARCHAR(500),
    image VARCHAR(255),
    archive TINYINT(1) DEFAULT 0,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : actualites
-- =====================================================
CREATE TABLE IF NOT EXISTS actualites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(300) NOT NULL,
    contenu TEXT NOT NULL,
    categorie ENUM('Prix','Soutenance','Publication Majeure','Annonce') NOT NULL,
    image VARCHAR(255),
    auteur_id INT,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    mis_en_avant TINYINT(1) DEFAULT 0,
    visible TINYINT(1) DEFAULT 1,
    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : partenaires
-- =====================================================
CREATE TABLE IF NOT EXISTS partenaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    logo VARCHAR(255),
    pays VARCHAR(100),
    site_web VARCHAR(500),
    type ENUM('National','International') NOT NULL,
    description TEXT,
    cree_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : newsletter
-- =====================================================
CREATE TABLE IF NOT EXISTS newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    token VARCHAR(64),
    actif TINYINT(1) DEFAULT 1,
    inscrit_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE : messages_contact
-- =====================================================
CREATE TABLE IF NOT EXISTS messages_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150),
    sujet VARCHAR(200),
    message TEXT,
    lu TINYINT(1) DEFAULT 0,
    recu_le TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- INSERTIONS DES DONNÉES EXEMPLES
-- =====================================================

-- Utilisateur admin (mot de passe: Admin@2025 hashé)
INSERT INTO utilisateurs (username, email, mot_de_passe, role) VALUES
('admin', 'admin@iutfv.cm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('editeur1', 'editeur@iutfv.cm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editeur');

-- Axes de recherche
INSERT INTO axes_recherche (titre, description, icone) VALUES
('Informatique & Intelligence Artificielle', 'Recherche sur l''IA, le machine learning, le traitement des données et les systèmes intelligents.', 'fa-brain'),
('Génie Civil & Matériaux', 'Étude des matériaux de construction, structures et développement durable.', 'fa-building'),
('Électronique & Télécommunications', 'Systèmes embarqués, IoT, réseaux et communications numériques.', 'fa-microchip'),
('Mathématiques Appliquées', 'Modélisation mathématique, optimisation et analyse numérique.', 'fa-calculator');

-- Chercheurs
INSERT INTO chercheurs (nom, prenom, grade, specialite, biographie, email, axe_id, statut, date_integration) VALUES
('Kamga', 'Jean-Pierre', 'Professeur', 'Intelligence Artificielle', 'Expert en IA avec plus de 15 ans d''expérience. Auteur de nombreuses publications internationales.', 'jp.kamga@iutfv.cm', 1, 'actif', '2018-09-01'),
('Ngono', 'Marie-Claire', 'MCF', 'Machine Learning', 'Spécialiste du deep learning et des réseaux de neurones appliqués à la santé.', 'mc.ngono@iutfv.cm', 1, 'actif', '2019-09-01'),
('Tchinda', 'Robert', 'Professeur', 'Matériaux de Construction', 'Pionnier dans l''étude des matériaux locaux pour la construction durable.', 'r.tchinda@iutfv.cm', 2, 'actif', '2017-09-01'),
('Fotso', 'Anne-Marie', 'Doctorant', 'Structures Béton', 'Doctorante travaillant sur l''optimisation des structures en béton armé.', 'am.fotso@iutfv.cm', 2, 'actif', '2022-09-01'),
('Mbarga', 'Patrick', 'Ingénieur', 'IoT et Capteurs', 'Ingénieur spécialisé dans les systèmes IoT pour l''agriculture intelligente.', 'p.mbarga@iutfv.cm', 3, 'actif', '2020-03-01'),
('Nguekam', 'Sophie', 'Post-doc', 'Traitement du Signal', 'Post-doctorante experte en traitement du signal pour les télécommunications.', 's.nguekam@iutfv.cm', 3, 'actif', '2023-01-15');

-- Mise à jour des responsables d'axes
UPDATE axes_recherche SET responsable_id = 1 WHERE id = 1;
UPDATE axes_recherche SET responsable_id = 3 WHERE id = 2;

-- Publications
INSERT INTO publications (titre, resume, annee, type, mots_cles, axe_id, langue, visible) VALUES
('Deep Learning pour la Détection Précoce du Paludisme', 'Cette étude présente un modèle de deep learning capable de détecter le paludisme à partir d''images de frottis sanguins avec une précision de 98%.', 2024, 'Article', 'IA, Santé, Deep Learning', 1, 'FR', 1),
('Optimisation des Bétons Locaux au Cameroun', 'Analyse des propriétés mécaniques des bétons fabriqués avec des granulats locaux camerounais.', 2023, 'Thèse', 'Matériaux, Béton, Construction', 2, 'FR', 1),
('Système IoT pour le Suivi Agricole', 'Développement d''un réseau de capteurs sans fil pour le monitoring des cultures en zone rurale.', 2024, 'Conférence', 'IoT, Agriculture, Capteurs', 3, 'FR', 1),
('Algorithmes Génétiques pour l''Optimisation Combinatoire', 'Application des algorithmes évolutionnaires à la résolution de problèmes d''optimisation complexe.', 2023, 'Article', 'Optimisation, Algorithmes, Mathématiques', 4, 'EN', 1),
('Réseaux de Neurones Convolutifs pour la Classification d''Images', 'Étude comparative des architectures CNN pour la classification d''images médicales.', 2024, 'Article', 'CNN, IA, Images Médicales', 1, 'EN', 1),
('Analyse Structurale des Ponts en Zone Sismique', 'Modélisation et simulation du comportement des ponts sous charges sismiques.', 2022, 'Rapport', 'Structures, Sismique, Génie Civil', 2, 'FR', 1),
('Protocoles de Communication pour l''IoT Industriel', 'Évaluation des protocoles MQTT, CoAP et AMQP pour les applications industrielles.', 2023, 'Conférence', 'IoT, Protocoles, Industrie', 3, 'FR', 1),
('Méthodes Numériques pour les EDP Non Linéaires', 'Développement de schémas numériques robustes pour la résolution d''équations aux dérivées partielles.', 2024, 'Article', 'EDP, Numérique, Mathématiques', 4, 'FR', 1);

-- Auteurs des publications
INSERT INTO publication_auteurs (publication_id, chercheur_id) VALUES
(1, 1), (1, 2),
(2, 3), (2, 4),
(3, 5),
(4, 6),
(5, 1), (5, 2),
(6, 3),
(7, 5), (7, 6),
(8, 6);

-- Projets
INSERT INTO projets (titre, description, objectifs, date_debut, date_fin, statut, financeur, axe_id, visible) VALUES
('IA pour la Santé Africaine', 'Développement de solutions d''IA adaptées aux maladies tropicales négligées.', 'Créer des outils de diagnostic assisté par IA accessibles dans les zones rurales.', '2023-01-01', '2025-12-31', 'En cours', 'ANR Cameroun', 1, 1),
('Béton Écologique à Base de Latérite', 'Valorisation de la latérite comme matériau de construction écologique.', 'Développer un béton performant utilisant 40% de latérite locale.', '2022-06-01', '2024-06-30', 'En cours', 'Ministère de la Recherche', 2, 1),
('Smart Farm Cameroon', 'Plateforme IoT complète pour l''agriculture de précision.', 'Déployer 100 stations de mesure dans 5 régions du Cameroun.', '2024-03-01', '2026-03-01', 'En cours', 'Union Européenne', 3, 1),
('Modélisation Mathématique des Épidémies', 'Application des mathématiques à la prédiction des épidémies.', 'Créer des modèles prédictifs pour les maladies infectieuses.', '2021-01-01', '2023-12-31', 'Terminé', 'OMS', 4, 1);

-- Membres des projets
INSERT INTO projet_chercheurs (projet_id, chercheur_id) VALUES
(1, 1), (1, 2),
(2, 3), (2, 4),
(3, 5), (3, 6),
(4, 6);

-- Événements
INSERT INTO evenements (titre, type, date_debut, date_fin, lieu, description, programme, inscription_requise, archive) VALUES
('Séminaire sur l''IA en Afrique', 'Séminaire', '2025-03-15 09:00:00', '2025-03-15 17:00:00', 'Amphithéâtre A - IUT Fotso Victor', 'Journée dédiée aux applications de l''intelligence artificielle dans le contexte africain.', '09h00: Ouverture\n10h00: Keynote - IA et Santé\n14h00: Tables rondes\n16h00: Clôture', 1, 0),
('Atelier Matériaux de Construction', 'Atelier', '2025-04-20 08:30:00', '2025-04-22 17:00:00', 'Laboratoire GC - IUT', 'Formation pratique sur les techniques modernes de construction.', 'Jour 1: Matériaux locaux\nJour 2: Techniques de mise en œuvre\nJour 3: Visites de chantier', 1, 0),
('Conférence Internationale IoT 2024', 'Conférence', '2024-11-10 09:00:00', '2024-11-12 18:00:00', 'Palais des Congrès - Yaoundé', 'Conférence internationale sur l''Internet des Objets.', 'Programme complet disponible sur le site', 1, 1);

-- Actualités
INSERT INTO actualites (titre, contenu, categorie, mis_en_avant, visible) VALUES
('Publication dans Nature AI', 'Notre équipe a publié un article majeur dans la revue Nature AI sur l''application du deep learning au diagnostic médical.', 'Publication Majeure', 1, 1),
('Soutenance de thèse réussie', 'Anne-Marie Fotso a brillamment soutenu sa thèse sur les bétons écologiques avec la mention Très Honorable.', 'Soutenance', 0, 1),
('Nouveau projet européen', 'Le laboratoire a obtenu un financement de 500 000€ de l''Union Européenne pour le projet Smart Farm.', 'Annonce', 1, 1),
('Prix du meilleur chercheur', 'Le Professeur Kamga a reçu le prix du meilleur chercheur en IA d''Afrique Centrale.', 'Prix', 0, 1);

-- Partenaires
INSERT INTO partenaires (nom, pays, site_web, type, description) VALUES
('Université de Yaoundé I', 'Cameroun', 'https://www.uy1.uninet.cm', 'National', 'Partenaire académique principal'),
('Institut de Recherche Géologique et Minière', 'Cameroun', 'https://www.irgm.cm', 'National', 'Collaboration sur les matériaux'),
('Université Paris-Saclay', 'France', 'https://www.universite-paris-saclay.fr', 'International', 'Co-tutelles de thèses et échanges'),
('MIT Africa Initiative', 'USA', 'https://africa.mit.edu', 'International', 'Partenariat recherche IA'),
('Agence Universitaire de la Francophonie', 'France', 'https://www.auf.org', 'International', 'Financement de projets collaboratifs');

-- Messages contact exemple
INSERT INTO messages_contact (nom, prenom, email, sujet, message) VALUES
('Dupont', 'Jean', 'jean.dupont@email.com', 'Demande de collaboration', 'Bonjour, je souhaiterais discuter d''une éventuelle collaboration.'),
('Martin', 'Sophie', 'sophie.martin@email.com', 'Information stage', 'Je suis intéressée par un stage au sein de votre laboratoire.');