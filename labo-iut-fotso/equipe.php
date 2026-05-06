<?php
/**
 * ÉQUIPE - Liste des chercheurs du laboratoire
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer tous les chercheurs actifs avec leur axe
$stmt = $pdo->prepare("
    SELECT c.*, a.titre as axe_titre
    FROM chercheurs c
    LEFT JOIN axes_recherche a ON c.axe_id = a.id
    WHERE c.statut = 'actif'
    ORDER BY c.grade, c.nom ASC
");
$stmt->execute();
$chercheurs = $stmt->fetchAll();

// Récupérer les grades uniques pour les filtres
$grades = ['Professeur', 'MCF', 'Doctorant', 'Ingénieur', 'Post-doc'];

$page_title = "Équipe de Recherche";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Notre Équipe" data-en="Our Team">Notre Équipe</h1>
            <p data-fr="Découvrez les chercheurs qui font vivre le laboratoire" data-en="Discover the researchers who bring the laboratory to life">
                Découvrez les chercheurs qui font vivre le laboratoire
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Barre de filtres -->
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all" data-fr="Tous" data-en="All">Tous</button>
                <?php foreach ($grades as $grade): ?>
                    <button class="filter-btn" data-filter="<?php echo htmlspecialchars($grade); ?>">
                        <?php echo htmlspecialchars($grade); ?>
                    </button>
                <?php endforeach; ?>
                <input type="text" id="search-input" class="search-input" 
                       placeholder="Rechercher un chercheur..." 
                       data-fr-placeholder="Rechercher un chercheur..." 
                       data-en-placeholder="Search a researcher...">
            </div>

            <!-- Grille des chercheurs -->
            <div class="grid grid-3" id="chercheurs-grid">
                <?php if (empty($chercheurs)): ?>
                    <div class="col-full">
                        <p class="empty-message" data-fr="Aucun chercheur enregistré pour le moment." data-en="No researcher registered at the moment.">
                            Aucun chercheur enregistré pour le moment.
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($chercheurs as $chercheur): ?>
                        <div class="card chercheur-card" 
                             data-grade="<?php echo htmlspecialchars($chercheur['grade']); ?>"
                             data-nom="<?php echo htmlspecialchars(strtolower($chercheur['nom'] . ' ' . $chercheur['prenom'])); ?>"
                             data-id="<?php echo $chercheur['id']; ?>">
                            
                            <div class="chercheur-photo">
                                <?php if ($chercheur['photo'] && file_exists($chercheur['photo'])): ?>
                                    <img src="<?php echo htmlspecialchars($chercheur['photo']); ?>" 
                                         alt="Photo de <?php echo htmlspecialchars($chercheur['nom']); ?>">
                                <?php else: ?>
                                    <div class="photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="chercheur-info">
                                <h3><?php echo htmlspecialchars($chercheur['nom'] . ' ' . $chercheur['prenom']); ?></h3>
                                <span class="badge badge-<?php echo getGradeBadgeClass($chercheur['grade']); ?>">
                                    <?php echo htmlspecialchars($chercheur['grade']); ?>
                                </span>
                                <?php if ($chercheur['axe_titre']): ?>
                                    <p class="axe"><?php echo htmlspecialchars($chercheur['axe_titre']); ?></p>
                                <?php endif; ?>
                                <p class="specialite"><?php echo htmlspecialchars($chercheur['specialite']); ?></p>
                                <?php if ($chercheur['email']): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($chercheur['email']); ?>" class="email-link">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($chercheur['email']); ?>
                                    </a>
                                <?php endif; ?>
                                <button class="btn btn-secondary btn-sm modal-trigger" 
                                        data-modal="modal-chercheur-<?php echo $chercheur['id']; ?>"
                                        aria-label="Voir le profil complet">
                                    <span data-fr="Voir le profil" data-en="View profile">Voir le profil</span>
                                </button>
                            </div>
                        </div>

                        <!-- Modale profil chercheur -->
                        <div class="modal" id="modal-chercheur-<?php echo $chercheur['id']; ?>">
                            <div class="modal-content">
                                <button class="modal-close" aria-label="Fermer">&times;</button>
                                <div class="modal-header">
                                    <div class="chercheur-photo-large">
                                        <?php if ($chercheur['photo'] && file_exists($chercheur['photo'])): ?>
                                            <img src="<?php echo htmlspecialchars($chercheur['photo']); ?>" 
                                                 alt="Photo de <?php echo htmlspecialchars($chercheur['nom']); ?>">
                                        <?php else: ?>
                                            <div class="photo-placeholder-large">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h2><?php echo htmlspecialchars($chercheur['nom'] . ' ' . $chercheur['prenom']); ?></h2>
                                        <span class="badge badge-<?php echo getGradeBadgeClass($chercheur['grade']); ?>">
                                            <?php echo htmlspecialchars($chercheur['grade']); ?>
                                        </span>
                                        <?php if ($chercheur['axe_titre']): ?>
                                            <p class="axe"><?php echo htmlspecialchars($chercheur['axe_titre']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <?php if ($chercheur['biographie']): ?>
                                        <h3 data-fr="Biographie" data-en="Biography">Biographie</h3>
                                        <p><?php echo nl2br(htmlspecialchars($chercheur['biographie'])); ?></p>
                                    <?php endif; ?>
                                    
                                    <h3 data-fr="Publications" data-en="Publications">Publications</h3>
                                    <?php
                                    // Récupérer les publications de ce chercheur
                                    $pubStmt = $pdo->prepare("
                                        SELECT p.* 
                                        FROM publications p
                                        INNER JOIN publication_auteurs pa ON p.id = pa.publication_id
                                        WHERE pa.chercheur_id = ? AND p.visible = 1
                                        ORDER BY p.annee DESC
                                        LIMIT 5
                                    ");
                                    $pubStmt->execute([$chercheur['id']]);
                                    $publications = $pubStmt->fetchAll();
                                    ?>
                                    <?php if (empty($publications)): ?>
                                        <p data-fr="Aucune publication enregistrée." data-en="No publication registered.">Aucune publication enregistrée.</p>
                                    <?php else: ?>
                                        <ul class="publications-list">
                                            <?php foreach ($publications as $pub): ?>
                                                <li>
                                                    <strong><?php echo htmlspecialchars($pub['titre']); ?></strong>
                                                    <span class="meta">(<?php echo $pub['annee']; ?> - <?php echo $pub['type']; ?>)</span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
