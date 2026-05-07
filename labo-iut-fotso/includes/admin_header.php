
<?php
/**
 * ADMIN_HEADER - En-tête du panel administrateur
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

// Vérifier que ce fichier est inclus dans un contexte admin
if (!isset($page_title)) {
    $page_title = "Administration";
}

$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Admin - Laboratoire IUT Fotso Victor</title>
    <link rel="icon" href="image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'><text y='32' font-size='32'>🔬</text></svg>">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-microscope"></i>
                </div>
                <div class="logo-text">
                    <h3>Labo IUT FV</h3>
                    <span>Administration</span>
                </div>
                <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Basculer le menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="dashboard.php" class="<?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>

                    <li class="nav-category">Contenu</li>

                    <li>
                        <a href="chercheurs.php" class="<?php echo $current_page === 'chercheurs' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i>
                            <span>Chercheurs</span>
                        </a>
                    </li>
                    <li>
                        <a href="axes.php" class="<?php echo $current_page === 'axes' ? 'active' : ''; ?>">
                            <i class="fas fa-flask"></i>
                            <span>Axes de recherche</span>
                        </a>
                    </li>
                    <li>
                        <a href="publications.php" class="<?php echo $current_page === 'publications' ? 'active' : ''; ?>">
                            <i class="fas fa-book"></i>
                            <span>Publications</span>
                        </a>
                    </li>
                    <li>
                        <a href="projets.php" class="<?php echo $current_page === 'projets' ? 'active' : ''; ?>">
                            <i class="fas fa-project-diagram"></i>
                            <span>Projets</span>
                        </a>
                    </li>
                    <li>
                        <a href="evenements.php" class="<?php echo $current_page === 'evenements' ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Événements</span>
                        </a>
                    </li>
                    <li>
                        <a href="actualites.php" class="<?php echo $current_page === 'actualites' ? 'active' : ''; ?>">
                            <i class="fas fa-newspaper"></i>
                            <span>Actualités</span>
                        </a>
                    </li>
                    <li>
                        <a href="partenaires.php" class="<?php echo $current_page === 'partenaires' ? 'active' : ''; ?>">
                            <i class="fas fa-handshake"></i>
                            <span>Partenaires</span>
                        </a>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-category">Administration</li>

                    <li>
                        <a href="utilisateurs.php" class="<?php echo $current_page === 'utilisateurs' ? 'active' : ''; ?>">
                            <i class="fas fa-user-shield"></i>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-category">Site</li>

                    <li>
                        <a href="../index.php" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            <span>Voir le site</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="sidebar-footer">
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main" id="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title"><?php echo htmlspecialchars($page_title); ?></h1>
                </div>

                <div class="topbar-right">
                    <div class="user-menu">
                        <span class="user-name">
                            <i class="fas fa-user-circle"></i>
                            <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>
                        </span>
                        <span class="user-role badge badge-<?php echo isset($_SESSION['role']) ? ($_SESSION['role'] === 'admin' ? 'admin' : 'editeur') : ''; ?>">
                            <?php echo htmlspecialchars($_SESSION['role'] ?? 'Utilisateur'); ?>
                        </span>
                    </div>
                </div>
            </header>
