<?php
/**
 * ÉVÉNEMENTS - Liste des événements du laboratoire
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer les événements à venir (non archivés, date future)
$stmtFuture = $pdo->prepare("
    SELECT * FROM evenements
    WHERE archive = 0 AND date_debut >= NOW()
    ORDER BY date_debut ASC
");
$stmtFuture->execute();
$evenements_a_venir = $stmtFuture->fetchAll();

// Récupérer les événements passés (archivés ou date passée)
$stmtPast = $pdo->prepare("
    SELECT * FROM evenements
    WHERE archive = 1 OR date_debut < NOW()
    ORDER BY date_debut DESC
");
$stmtPast->execute();
$evenements_passes = $stmtPast->fetchAll();

$page_title = "Événements";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Événements" data-en="Events">Événements</h1>
            <p data-fr="Séminaires, conférences, ateliers et soutenances du laboratoire" 
               data-en="Laboratory seminars, conferences, workshops and defenses">
                Séminaires, conférences, ateliers et soutenances du laboratoire
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Événements à venir -->
            <h2 class="section-title">
                <i class="fas fa-calendar-alt"></i>
                <span data-fr="Événements à venir" data-en="Upcoming events">Événements à venir</span>
            </h2>
            
            <?php if (empty($evenements_a_venir)): ?>
                <p class="empty-message" data-fr="Aucun événement à venir pour le moment." data-en="No upcoming events at the moment.">
                    Aucun événement à venir pour le moment.
                </p>
            <?php else: ?>
                <div class="grid grid-2">
                    <?php foreach ($evenements_a_venir as $event): ?>
                        <div class="card evenement-card">
                            <?php if ($event['image'] && file_exists($event['image'])): ?>
                                <div class="event-image">
                                    <img src="<?php echo htmlspecialchars($event['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($event['titre']); ?>">
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-content">
                                <div class="event-header">
                                    <span class="badge badge-<?php echo getTypeBadgeClass($event['type']); ?>">
                                        <?php echo htmlspecialchars($event['type']); ?>
                                    </span>
                                    <?php if ($event['inscription_requise']): ?>
                                        <span class="badge badge-inscription">
                                            <i class="fas fa-user-plus"></i>
                                            <span data-fr="Inscription requise" data-en="Registration required">Inscription requise</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h3><?php echo htmlspecialchars($event['titre']); ?></h3>
                                
                                <div class="event-details">
                                    <div class="detail">
                                        <i class="fas fa-calendar"></i>
                                        <span><?php echo formatDateTimeFr($event['date_debut']); ?></span>
                                    </div>
                                    <?php if ($event['date_fin'] && $event['date_fin'] != $event['date_debut']): ?>
                                        <div class="detail">
                                            <i class="fas fa-calendar-check"></i>
                                            <span><?php echo formatDateTimeFr($event['date_fin']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($event['lieu']): ?>
                                        <div class="detail">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?php echo htmlspecialchars($event['lieu']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($event['description']): ?>
                                    <p class="description"><?php echo truncateText(htmlspecialchars($event['description']), 120); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($event['programme']): ?>
                                    <details class="programme">
                                        <summary data-fr="Voir le programme" data-en="View program">Voir le programme</summary>
                                        <p><?php echo nl2br(htmlspecialchars($event['programme'])); ?></p>
                                    </details>
                                <?php endif; ?>
                                
                                <div class="event-actions">
                                    <?php if ($event['inscription_requise'] && $event['lien_inscription']): ?>
                                        <a href="<?php echo htmlspecialchars($event['lien_inscription']); ?>" 
                                           target="_blank" 
                                           rel="noopener" 
                                           class="btn btn-primary">
                                            <i class="fas fa-ticket-alt"></i>
                                            <span data-fr="S'inscrire" data-en="Register">S'inscrire</span>
                                        </a>
                                    <?php elseif ($event['inscription_requise']): ?>
                                        <a href="mailto:labo.recherche@iutfv.cm?subject=Inscription%20-%20<?php echo urlencode($event['titre']); ?>" 
                                           class="btn btn-primary">
                                            <i class="fas fa-envelope"></i>
                                            <span data-fr="S'inscrire par email" data-en="Register by email">S'inscrire par email</span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($event['description']): ?>
                                        <button class="btn btn-secondary modal-trigger" 
                                                data-modal="modal-event-<?php echo $event['id']; ?>">
                                            <span data-fr="Plus d'infos" data-en="More info">Plus d'infos</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modale événement -->
                        <?php if ($event['description'] || $event['programme']): ?>
                        <div class="modal" id="modal-event-<?php echo $event['id']; ?>">
                            <div class="modal-content">
                                <button class="modal-close" aria-label="Fermer">&times;</button>
                                <div class="modal-header">
                                    <h2><?php echo htmlspecialchars($event['titre']); ?></h2>
                                    <span class="badge badge-<?php echo getTypeBadgeClass($event['type']); ?>">
                                        <?php echo htmlspecialchars($event['type']); ?>
                                    </span>
                                </div>
                                <div class="modal-body">
                                    <?php if ($event['image'] && file_exists($event['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($event['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($event['titre']); ?>" 
                                             class="modal-image">
                                    <?php endif; ?>
                                    
                                    <div class="event-meta">
                                        <p><i class="fas fa-calendar"></i> <?php echo formatDateTimeFr($event['date_debut']); ?></p>
                                        <?php if ($event['lieu']): ?>
                                            <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['lieu']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($event['description']): ?>
                                        <h3 data-fr="Description" data-en="Description">Description</h3>
                                        <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($event['programme']): ?>
                                        <h3 data-fr="Programme" data-en="Program">Programme</h3>
                                        <p><?php echo nl2br(htmlspecialchars($event['programme'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Événements passés -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title accordion-trigger">
                <i class="fas fa-history"></i>
                <span data-fr="Événements passés" data-en="Past events">Événements passés</span>
                <i class="fas fa-chevron-down accordion-icon"></i>
            </h2>
            
            <div class="accordion-content">
                <?php if (empty($evenements_passes)): ?>
                    <p class="empty-message" data-fr="Aucun événement passé enregistré." data-en="No past events registered.">
                        Aucun événement passé enregistré.
                    </p>
                <?php else: ?>
                    <div class="grid grid-2">
                        <?php foreach ($evenements_passes as $event): ?>
                            <div class="card evenement-card passe">
                                <div class="event-content">
                                    <div class="event-header">
                                        <span class="badge badge-<?php echo getTypeBadgeClass($event['type']); ?>">
                                            <?php echo htmlspecialchars($event['type']); ?>
                                        </span>
                                        <span class="badge badge-archive">
                                            <i class="fas fa-archive"></i>
                                            <span data-fr="Archivé" data-en="Archived">Archivé</span>
                                        </span>
                                    </div>
                                    
                                    <h3><?php echo htmlspecialchars($event['titre']); ?></h3>
                                    
                                    <div class="event-details">
                                        <div class="detail">
                                            <i class="fas fa-calendar"></i>
                                            <span><?php echo formatDateTimeFr($event['date_debut']); ?></span>
                                        </div>
                                        <?php if ($event['lieu']): ?>
                                            <div class="detail">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span><?php echo htmlspecialchars($event['lieu']); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
