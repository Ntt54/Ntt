<?php
/**
 * PROJETS - Liste des projets de recherche
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer tous les projets visibles avec leurs axes et chercheurs
$stmt = $pdo->prepare("
    SELECT p.*, a.titre as axe_titre
    FROM projets p
    LEFT JOIN axes_recherche a ON p.axe_id = a.id
    WHERE p.visible = 1
    ORDER BY 
        CASE p.statut 
            WHEN 'En cours' THEN 1 
            WHEN 'À venir' THEN 2 
            WHEN 'Terminé' THEN 3 
        END,
        p.date_debut DESC
");
$stmt->execute();
$projets = $stmt->fetchAll();

// Récupérer les chercheurs pour chaque projet
foreach ($projets as &$projet) {
    $chercheursStmt = $pdo->prepare("
        SELECT c.nom, c.prenom 
        FROM chercheurs c
        INNER JOIN projet_chercheurs pc ON c.id = pc.chercheur_id
        WHERE pc.projet_id = ?
    ");
    $chercheursStmt->execute([$projet['id']]);
    $projet['chercheurs'] = $chercheursStmt->fetchAll();
}
unset($projet);

$page_title = "Projets de Recherche";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Projets de Recherche" data-en="Research Projects">Projets de Recherche</h1>
            <p data-fr="Découvrez nos projets en cours et terminés" data-en="Discover our ongoing and completed projects">
                Découvrez nos projets en cours et terminés
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Barre de filtres -->
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all" data-fr="Tous" data-en="All">Tous</button>
                <button class="filter-btn" data-filter="En cours" data-fr="En cours" data-en="Ongoing">
                    <i class="fas fa-circle-notch"></i> <span data-fr="En cours" data-en="Ongoing">En cours</span>
                </button>
                <button class="filter-btn" data-filter="À venir" data-fr="À venir" data-en="Upcoming">
                    <i class="fas fa-clock"></i> <span data-fr="À venir" data-en="Upcoming">À venir</span>
                </button>
                <button class="filter-btn" data-filter="Terminé" data-fr="Terminé" data-en="Completed">
                    <i class="fas fa-check-circle"></i> <span data-fr="Terminé" data-en="Completed">Terminé</span>
                </button>
                <input type="text" id="search-input" class="search-input" 
                       placeholder="Rechercher un projet..." 
                       data-fr-placeholder="Rechercher un projet..." 
                       data-en-placeholder="Search a project...">
            </div>

            <!-- Liste des projets -->
            <div class="grid grid-2" id="projets-grid">
                <?php if (empty($projets)): ?>
                    <div class="col-full">
                        <p class="empty-message" data-fr="Aucun projet enregistré pour le moment." data-en="No project registered at the moment.">
                            Aucun projet enregistré pour le moment.
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($projets as $projet): ?>
                        <div class="card projet-card" 
                             data-statut="<?php echo htmlspecialchars($projet['statut']); ?>"
                             data-titre="<?php echo htmlspecialchars(strtolower($projet['titre'])); ?>">
                            
                            <div class="projet-header">
                                <span class="badge badge-<?php echo getStatutBadgeClass($projet['statut']); ?>">
                                    <?php echo htmlspecialchars($projet['statut']); ?>
                                </span>
                                <?php if ($projet['axe_titre']): ?>
                                    <span class="axe-badge">
                                        <i class="fas fa-flask"></i>
                                        <?php echo htmlspecialchars($projet['axe_titre']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h3><?php echo htmlspecialchars($projet['titre']); ?></h3>
                            
                            <?php if ($projet['description']): ?>
                                <p class="description"><?php echo truncateText(htmlspecialchars($projet['description']), 150); ?></p>
                            <?php endif; ?>
                            
                            <?php if ($projet['objectifs']): ?>
                                <div class="objectifs">
                                    <h4 data-fr="Objectifs" data-en="Objectives">Objectifs</h4>
                                    <p><?php echo truncateText(htmlspecialchars($projet['objectifs']), 100); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="projet-dates">
                                <?php if ($projet['date_debut']): ?>
                                    <div class="date">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span data-fr="Début:" data-en="Start:">Début:</span>
                                        <?php echo formatDateFr($projet['date_debut']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($projet['date_fin']): ?>
                                    <div class="date">
                                        <i class="fas fa-calendar-check"></i>
                                        <span data-fr="Fin:" data-en="End:">Fin:</span>
                                        <?php echo formatDateFr($projet['date_fin']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($projet['financeur']): ?>
                                <div class="financeur">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    <span data-fr="Financeur:" data-en="Funder:">Financeur:</span>
                                    <?php echo htmlspecialchars($projet['financeur']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($projet['chercheurs'])): ?>
                                <div class="equipe">
                                    <h4 data-fr="Équipe" data-en="Team">Équipe</h4>
                                    <ul class="chercheurs-list">
                                        <?php foreach ($projet['chercheurs'] as $chercheur): ?>
                                            <li><?php echo htmlspecialchars($chercheur['prenom'] . ' ' . $chercheur['nom']); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($projet['livrables']): ?>
                                <div class="livrables">
                                    <h4 data-fr="Livrables" data-en="Deliverables">Livrables</h4>
                                    <p><?php echo nl2br(htmlspecialchars($projet['livrables'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <h2 data-fr="Participer à nos projets" data-en="Participate in our projects">Participer à nos projets</h2>
            <p data-fr="Vous êtes intéressé par nos travaux de recherche ? N'hésitez pas à nous contacter pour discuter de collaborations potentielles." 
               data-en="Are you interested in our research work? Do not hesitate to contact us to discuss potential collaborations.">
                Vous êtes intéressé par nos travaux de recherche ? N'hésitez pas à nous contacter pour discuter de collaborations potentielles.
            </p>
            <a href="contact.php" class="btn btn-primary">
                <i class="fas fa-envelope"></i>
                <span data-fr="Nous contacter" data-en="Contact us">Nous contacter</span>
            </a>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
