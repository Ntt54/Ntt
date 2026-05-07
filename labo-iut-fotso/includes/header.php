
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/**
 * En-tête HTML global pour le site public
 * Laboratoire de Recherche - IUT Fotso Victor
 */
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

// Déterminer la page active
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Laboratoire de Recherche de l'IUT Fotso Victor de Bandjoun - Excellence scientifique au cœur du Cameroun">
    <meta name="keywords" content="recherche, laboratoire, IUT, Fotso Victor, Bandjoun, Cameroun, science, innovation">

    <title>Laboratoire de Recherche - IUT Fotso Victor</title>

    <!-- Favicon -->
    <link rel="icon" href="image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'><text y='32' font-size='32'>🔬</text></svg>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <i class="fas fa-flask"></i>
                    <span data-fr="Laboratoire de Recherche" data-en="Research Laboratory">Laboratoire de Recherche</span>
                    <small>IUT Fotso Victor</small>
                </a>

                <nav class="main-nav" id="mainNav">
                    <ul>
                        <li><a href="index.php" class="<?= $currentPage === 'index' ? 'active' : '' ?>" data-fr="Accueil" data-en="Home">Accueil</a></li>
                        <li><a href="about.php" class="<?= $currentPage === 'about' ? 'active' : '' ?>" data-fr="À propos" data-en="About">À propos</a></li>
                        <li><a href="equipe.php" class="<?= $currentPage === 'equipe' ? 'active' : '' ?>" data-fr="Équipe" data-en="Team">Équipe</a></li>
                        <li><a href="axes.php" class="<?= $currentPage === 'axes' ? 'active' : '' ?>" data-fr="Axes" data-en="Research Areas">Axes</a></li>
                        <li><a href="publications.php" class="<?= $currentPage === 'publications' ? 'active' : '' ?>" data-fr="Publications" data-en="Publications">Publications</a></li>
                        <li><a href="projets.php" class="<?= $currentPage === 'projets' ? 'active' : '' ?>" data-fr="Projets" data-en="Projects">Projets</a></li>
                        <li><a href="evenements.php" class="<?= $currentPage === 'evenements' ? 'active' : '' ?>" data-fr="Événements" data-en="Events">Événements</a></li>
                        <li><a href="actualites.php" class="<?= $currentPage === 'actualites' ? 'active' : '' ?>" data-fr="Actualités" data-en="News">Actualités</a></li>
                        <li><a href="partenaires.php" class="<?= $currentPage === 'partenaires' ? 'active' : '' ?>" data-fr="Partenaires" data-en="Partners">Partenaires</a></li>
                        <li><a href="contact.php" class="<?= $currentPage === 'contact' ? 'active' : '' ?>" data-fr="Contact" data-en="Contact">Contact</a></li>
                    </ul>
                </nav>

                <div class="header-actions">
                    <!-- Language Switcher -->
                    <div class="lang-switcher">
                        <button class="lang-btn active" data-lang="fr">FR</button>
                        <span>|</span>
                        <button class="lang-btn" data-lang="en">EN</button>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="text" id="globalSearch" placeholder="Rechercher..." aria-label="Recherche">
                        <button type="button" aria-label="Rechercher"><i class="fas fa-search"></i></button>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button class="menu-toggle" id="menuToggle" aria-label="Menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <main class="main-content">
