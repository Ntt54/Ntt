<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Page d'accueil - Laboratoire de Recherche IUT Fotso Victor
 */

require_once __DIR__ . '/includes/header.php';

// Récupérer les statistiques
$stats = [
    'chercheurs' => $pdo->query("SELECT COUNT(*) FROM chercheurs WHERE statut='actif'")->fetchColumn(),
    'publications' => $pdo->query("SELECT COUNT(*) FROM publications WHERE visible=1")->fetchColumn(),
    'projets' => $pdo->query("SELECT COUNT(*) FROM projets WHERE statut='En cours' AND visible=1")->fetchColumn(),
    'evenements' => $pdo->query("SELECT COUNT(*) FROM evenements WHERE date_debut > NOW() AND archive=0")->fetchColumn()
];

// Récupérer les axes de recherche
$axes = $pdo->query("
    SELECT a.*, COUNT(c.id) as nb_chercheurs,
           (SELECT CONCAT(pr.nom, ' ', pr.prenom) FROM chercheurs pr WHERE pr.id = a.responsable_id) as responsable_nom
    FROM axes_recherche a
    LEFT JOIN chercheurs c ON c.axe_id = a.id AND c.statut='actif'
    GROUP BY a.id
    ORDER BY a.titre
")->fetchAll();

// Récupérer les dernières actualités (mis_en_avant en priorité)
$actualites = $pdo->query("
    SELECT a.*, u.username as auteur_username
    FROM actualites a
    LEFT JOIN utilisateurs u ON u.id = a.auteur_id
    WHERE a.visible = 1
    ORDER BY a.mis_en_avant DESC, a.date_publication DESC
    LIMIT 3
")->fetchAll();

// Récupérer les prochains événements
$evenements = $pdo->query("
    SELECT * FROM evenements
    WHERE date_debut > NOW() AND archive = 0
    ORDER BY date_debut ASC
    LIMIT 2
")->fetchAll();

// Récupérer tous les partenaires pour le défilement
$partenaires = $pdo->query("SELECT * FROM partenaires ORDER BY type, nom")->fetchAll();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <h1 data-fr="Laboratoire de Recherche – IUT Fotso Victor" data-en="Research Laboratory – IUT Fotso Victor">Laboratoire de Recherche – IUT Fotso Victor</h1>
        <p data-fr="Excellence scientifique au cœur du Cameroun" data-en="Scientific excellence in the heart of Cameroon">Excellence scientifique au cœur du Cameroun</p>
        <a href="about.php" class="btn btn-primary" data-fr="Découvrir le laboratoire" data-en="Discover the laboratory">Découvrir le laboratoire</a>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item fade-in">
                <span class="stat-number"><?= e($stats['chercheurs']) ?></span>
                <span class="stat-label" data-fr="Chercheurs Actifs" data-en="Active Researchers">Chercheurs Actifs</span>
            </div>
            <div class="stat-item fade-in">
                <span class="stat-number"><?= e($stats['publications']) ?></span>
                <span class="stat-label" data-fr="Publications" data-en="Publications">Publications</span>
            </div>
            <div class="stat-item fade-in">
                <span class="stat-number"><?= e($stats['projets']) ?></span>
                <span class="stat-label" data-fr="Projets en Cours" data-en="Ongoing Projects">Projets en Cours</span>
            </div>
            <div class="stat-item fade-in">
                <span class="stat-number"><?= e($stats['evenements']) ?></span>
                <span class="stat-label" data-fr="Événements à Venir" data-en="Upcoming Events">Événements à Venir</span>
            </div>
        </div>
    </div>
</section>

<!-- Axes de Recherche -->
<section class="section">
    <div class="container">
        <div class="section-title fade-in">
            <h2 data-fr="Axes de Recherche" data-en="Research Areas">Axes de Recherche</h2>
            <p data-fr="Nos domaines d'expertise scientifique" data-en="Our scientific areas of expertise">Nos domaines d'expertise scientifique</p>
        </div>

        <?php if (!empty($axes)): ?>
        <div class="cards-grid">
            <?php foreach ($axes as $axe): ?>
            <div class="card fade-in">
                <div style="font-size: 3rem; color: var(--vert-labo); margin-bottom: 1rem;">
                    <i class="fas <?= e($axe['icone'] ?? 'fa-flask') ?>"></i>
                </div>
                <h3 class="card-title"><?= e($axe['titre']) ?></h3>
                <p class="card-text"><?= excerpt($axe['description'], 120) ?></p>
                <?php if ($axe['responsable_nom']): ?>
                <p><small><strong>Responsable:</strong> <?= e($axe['responsable_nom']) ?></small></p>
                <?php endif; ?>
                <p><small><i class="fas fa-users"></i> <?= e($axe['nb_chercheurs']) ?> chercheur(s)</small></p>
                <a href="axes.php" class="btn btn-secondary btn-small" data-fr="Voir les publications" data-en="View publications">Voir les publications</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h3 data-fr="Aucun axe de recherche disponible" data-en="No research areas available">Aucun axe de recherche disponible</h3>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Dernières Actualités -->
<section class="section" style="background: white;">
    <div class="container">
        <div class="section-title fade-in">
            <h2 data-fr="Dernières Actualités" data-en="Latest News">Dernières Actualités</h2>
            <p data-fr="Restez informé de nos dernières nouvelles" data-en="Stay informed about our latest news">Restez informé de nos dernières nouvelles</p>
        </div>

        <?php if (!empty($actualites)): ?>
        <div class="cards-grid">
            <?php foreach ($actualites as $actu): ?>
            <div class="card fade-in">
                <?php if ($actu['image']): ?>
                <img src="uploads/images/<?= e($actu['image']) ?>" alt="<?= e($actu['titre']) ?>" class="card-image">
                <?php endif; ?>
                <span class="badge badge-<?= $actu['categorie'] === 'Prix' ? 'warning' : ($actu['categorie'] === 'Publication Majeure' ? 'success' : 'info') ?>">
                    <?= e($actu['categorie']) ?>
                </span>
                <h3 class="card-title" style="margin-top: 0.5rem;"><?= e($actu['titre']) ?></h3>
                <p class="card-text"><?= excerpt($actu['contenu'], 100) ?></p>
                <small><i class="far fa-calendar"></i> <?= formatDateFr($actu['date_publication']) ?></small>
                <br>
                <a href="actualites.php" class="btn btn-primary btn-small" style="margin-top: 1rem;" data-fr="Lire la suite" data-en="Read more">Lire la suite</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="far fa-newspaper"></i>
            <h3 data-fr="Aucune actualité disponible" data-en="No news available">Aucune actualité disponible</h3>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Événements à Venir -->
<section class="section">
    <div class="container">
        <div class="section-title fade-in">
            <h2 data-fr="Événements à Venir" data-en="Upcoming Events">Événements à Venir</h2>
            <p data-fr="Participez à nos séminaires et conférences" data-en="Join our seminars and conferences">Participez à nos séminaires et conférences</p>
        </div>

        <?php if (!empty($evenements)): ?>
        <div class="cards-grid">
            <?php foreach ($evenements as $event): ?>
            <div class="card fade-in">
                <span class="badge badge-info"><?= e($event['type']) ?></span>
                <h3 class="card-title" style="margin-top: 0.5rem;"><?= e($event['titre']) ?></h3>
                <p class="card-text"><?= excerpt($event['description'], 100) ?></p>
                <p><i class="far fa-calendar"></i> <?= formatDateTimeFr($event['date_debut']) ?></p>
                <p><i class="fas fa-map-marker-alt"></i> <?= e($event['lieu']) ?></p>
                <?php if ($event['inscription_requise']): ?>
                <a href="mailto:labo.recherche@iutfv.cm?subject=Inscription=<?= urlencode($event['titre']) ?>" class="btn btn-primary btn-small" data-fr="S'inscrire" data-en="Register">S'inscrire</a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="far fa-calendar-times"></i>
            <h3 data-fr="Aucun événement à venir" data-en="No upcoming events">Aucun événement à venir</h3>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Partenaires -->
<section class="section" style="background: white;">
    <div class="container">
        <div class="section-title fade-in">
            <h2 data-fr="Nos Partenaires" data-en="Our Partners">Nos Partenaires</h2>
            <p data-fr="Ils nous font confiance" data-en="They trust us">Ils nous font confiance</p>
        </div>

        <?php if (!empty($partenaires)): ?>
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; align-items: center;">
            <?php foreach ($partenaires as $partenaire): ?>
            <div style="text-align: center; padding: 1rem; background: var(--blanc-casse); border-radius: 8px; min-width: 150px;" class="fade-in">
                <?php if ($partenaire['logo']): ?>
                <img src="uploads/logos/<?= e($partenaire['logo']) ?>" alt="<?= e($partenaire['nom']) ?>" style="height: 60px; margin: 0 auto 0.5rem;">
                <?php else: ?>
                <div style="font-size: 2rem; color: var(--bleu-fonce); font-weight: bold;">
                    <?= strtoupper(substr($partenaire['nom'], 0, 2)) ?>
                </div>
                <?php endif; ?>
                <p style="font-size: 0.85rem; font-weight: 600;"><?= e($partenaire['nom']) ?></p>
                <p style="font-size: 0.75rem; color: var(--gris-texte);"><?= e($partenaire['pays']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="far fa-handshake"></i>
            <h3 data-fr="Aucun partenaire répertorié" data-en="No partners listed">Aucun partenaire répertorié</h3>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
