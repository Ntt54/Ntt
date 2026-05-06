<?php
/**
 * ACTUALITÉS - Liste des actualités du laboratoire
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer toutes les actualités visibles
$stmt = $pdo->prepare("
    SELECT a.*, u.username as auteur_nom
    FROM actualites a
    LEFT JOIN utilisateurs u ON a.auteur_id = u.id
    WHERE a.visible = 1
    ORDER BY a.mis_en_avant DESC, a.date_publication DESC
");
$stmt->execute();
$actualites = $stmt->fetchAll();

// Catégories pour les filtres
$categories = ['Prix', 'Soutenance', 'Publication Majeure', 'Annonce'];

$page_title = "Actualités";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Actualités" data-en="News">Actualités</h1>
            <p data-fr="Restez informé des dernières nouvelles du laboratoire" 
               data-en="Stay informed of the latest laboratory news">
                Restez informé des dernières nouvelles du laboratoire
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Barre de filtres -->
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all" data-fr="Toutes" data-en="All">Toutes</button>
                <?php foreach ($categories as $categorie): ?>
                    <button class="filter-btn" data-filter="<?php echo htmlspecialchars($categorie); ?>">
                        <?php echo htmlspecialchars($categorie); ?>
                    </button>
                <?php endforeach; ?>
                <input type="text" id="search-input" class="search-input" 
                       placeholder="Rechercher..." 
                       data-fr-placeholder="Rechercher..." 
                       data-en-placeholder="Search...">
            </div>

            <!-- Liste des actualités -->
            <div class="grid grid-3" id="actualites-grid">
                <?php if (empty($actualites)): ?>
                    <div class="col-full">
                        <p class="empty-message" data-fr="Aucune actualité disponible pour le moment." data-en="No news available at the moment.">
                            Aucune actualité disponible pour le moment.
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($actualites as $actu): ?>
                        <div class="card actualite-card" 
                             data-categorie="<?php echo htmlspecialchars($actu['categorie']); ?>"
                             data-titre="<?php echo htmlspecialchars(strtolower($actu['titre'])); ?>">
                            
                            <?php if ($actu['image'] && file_exists($actu['image'])): ?>
                                <div class="actualite-image">
                                    <img src="<?php echo htmlspecialchars($actu['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($actu['titre']); ?>">
                                    <?php if ($actu['mis_en_avant']): ?>
                                        <span class="badge badge-featured">
                                            <i class="fas fa-star"></i>
                                            <span data-fr="À la une" data-en="Featured">À la une</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="actualite-image placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="actualite-content">
                                <div class="actualite-header">
                                    <span class="badge badge-<?php echo getCategorieBadgeClass($actu['categorie']); ?>">
                                        <?php echo htmlspecialchars($actu['categorie']); ?>
                                    </span>
                                    <span class="date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo formatDateFr($actu['date_publication']); ?>
                                    </span>
                                </div>
                                
                                <h3><?php echo htmlspecialchars($actu['titre']); ?></h3>
                                
                                <p class="extrait"><?php echo truncateText(strip_tags($actu['contenu']), 150); ?></p>
                                
                                <?php if ($actu['auteur_nom']): ?>
                                    <p class="auteur">
                                        <i class="fas fa-user-edit"></i>
                                        <span data-fr="Par:" data-en="By:">Par:</span>
                                        <?php echo htmlspecialchars($actu['auteur_nom']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <button class="btn btn-primary btn-sm modal-trigger" 
                                        data-modal="modal-actu-<?php echo $actu['id']; ?>">
                                    <span data-fr="Lire la suite" data-en="Read more">Lire la suite</span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Modale actualité -->
                        <div class="modal" id="modal-actu-<?php echo $actu['id']; ?>">
                            <div class="modal-content modal-large">
                                <button class="modal-close" aria-label="Fermer">&times;</button>
                                <div class="modal-header">
                                    <span class="badge badge-<?php echo getCategorieBadgeClass($actu['categorie']); ?>">
                                        <?php echo htmlspecialchars($actu['categorie']); ?>
                                    </span>
                                    <span class="date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo formatDateFr($actu['date_publication']); ?>
                                    </span>
                                </div>
                                <div class="modal-body">
                                    <?php if ($actu['image'] && file_exists($actu['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($actu['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($actu['titre']); ?>" 
                                             class="modal-image">
                                    <?php endif; ?>
                                    
                                    <h2><?php echo htmlspecialchars($actu['titre']); ?></h2>
                                    
                                    <?php if ($actu['auteur_nom']): ?>
                                        <p class="auteur">
                                            <i class="fas fa-user-edit"></i>
                                            <span data-fr="Par:" data-en="By:">Par:</span>
                                            <?php echo htmlspecialchars($actu['auteur_nom']); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <div class="contenu-complet">
                                        <?php echo nl2br(htmlspecialchars($actu['contenu'])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Section abonnement newsletter -->
    <section class="section section-alt newsletter-section">
        <div class="container">
            <div class="newsletter-box">
                <i class="fas fa-envelope-open-text"></i>
                <h2 data-fr="Restez informé" data-en="Stay informed">Restez informé</h2>
                <p data-fr="Abonnez-vous à notre newsletter pour recevoir nos dernières actualités." 
                   data-en="Subscribe to our newsletter to receive our latest news.">
                    Abonnez-vous à notre newsletter pour recevoir nos dernières actualités.
                </p>
                <form action="contact.php?action=newsletter" method="POST" class="newsletter-form">
                    <input type="email" name="email" required 
                           placeholder="Votre email" 
                           data-fr-placeholder="Votre email" 
                           data-en-placeholder="Your email">
                    <button type="submit" class="btn btn-primary">
                        <span data-fr="S'abonner" data-en="Subscribe">S'abonner</span>
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
