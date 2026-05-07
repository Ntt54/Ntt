
<?php
/**
 * AXES DE RECHERCHE - Liste des axes du laboratoire
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer tous les axes avec le nombre de chercheurs et publications
$stmt = $pdo->prepare("
    SELECT a.*,
           COUNT(DISTINCT c.id) as nb_chercheurs,
           COUNT(DISTINCT p.id) as nb_publications,
           resp.nom as responsable_nom,
           resp.prenom as responsable_prenom
    FROM axes_recherche a
    LEFT JOIN chercheurs c ON a.id = c.axe_id AND c.statut = 'actif'
    LEFT JOIN publications p ON a.id = p.axe_id AND p.visible = 1
    LEFT JOIN chercheurs resp ON a.responsable_id = resp.id
    GROUP BY a.id
    ORDER BY a.titre ASC
");
$stmt->execute();
$axes = $stmt->fetchAll();

$page_title = "Axes de Recherche";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Axes de Recherche" data-en="Research Areas">Axes de Recherche</h1>
            <p data-fr="Les domaines d'excellence scientifique du laboratoire" data-en="The scientific excellence areas of the laboratory">
                Les domaines d'excellence scientifique du laboratoire
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <?php if (empty($axes)): ?>
                <p class="empty-message" data-fr="Aucun axe de recherche enregistré pour le moment." data-en="No research area registered at the moment.">
                    Aucun axe de recherche enregistré pour le moment.
                </p>
            <?php else: ?>
                <div class="grid grid-2">
                    <?php foreach ($axes as $axe): ?>
                        <div class="card axe-card">
                            <div class="axe-icon">
                                <i class="fas <?php echo htmlspecialchars($axe['icone'] ?? 'fa-flask'); ?>"></i>
                            </div>
                            <div class="axe-content">
                                <h2><?php echo htmlspecialchars($axe['titre']); ?></h2>
                                <?php if ($axe['responsable_nom']): ?>
                                    <p class="responsable">
                                        <i class="fas fa-user-tie"></i>
                                        <span data-fr="Responsable:" data-en="Head:">Responsable:</span>
                                        <?php echo htmlspecialchars($axe['responsable_prenom'] . ' ' . $axe['responsable_nom']); ?>
                                    </p>
                                <?php endif; ?>
                                <p class="description"><?php echo nl2br(htmlspecialchars($axe['description'])); ?></p>
                                <div class="axe-stats">
                                    <span class="stat">
                                        <i class="fas fa-users"></i>
                                        <?php echo $axe['nb_chercheurs']; ?>
                                        <span data-fr="chercheur(s)" data-en="researcher(s)">chercheur(s)</span>
                                    </span>
                                    <span class="stat">
                                        <i class="fas fa-book"></i>
                                        <?php echo $axe['nb_publications']; ?>
                                        <span data-fr="publication(s)" data-en="publication(s)">publication(s)</span>
                                    </span>
                                </div>
                                <a href="publications.php?axe=<?php echo $axe['id']; ?>" class="btn btn-primary">
                                    <span data-fr="Voir les publications" data-en="View publications">Voir les publications</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <h2 data-fr="Pourquoi nos axes de recherche ?" data-en="Why our research areas?">Pourquoi nos axes de recherche ?</h2>
            <p data-fr="Nos axes de recherche sont conçus pour répondre aux défis scientifiques et technologiques actuels, tout en s'inscrivant dans les priorités nationales de développement du Cameroun."
               data-en="Our research areas are designed to meet current scientific and technological challenges, while aligning with Cameroon's national development priorities.">
                Nos axes de recherche sont conçus pour répondre aux défis scientifiques et technologiques actuels,
                tout en s'inscrivant dans les priorités nationales de développement du Cameroun.
            </p>
            <div class="grid grid-3 features">
                <div class="feature">
                    <i class="fas fa-bullseye"></i>
                    <h3 data-fr="Objectifs clairs" data-en="Clear objectives">Objectifs clairs</h3>
                    <p data-fr="Chaque axe dispose d'objectifs de recherche précis et mesurables."
                       data-en="Each area has precise and measurable research objectives.">
                        Chaque axe dispose d'objectifs de recherche précis et mesurables.
                    </p>
                </div>
                <div class="feature">
                    <i class="fas fa-handshake"></i>
                    <h3 data-fr="Collaboration" data-en="Collaboration">Collaboration</h3>
                    <p data-fr="Travail en équipe et partenariats nationaux et internationaux."
                       data-en="Teamwork and national/international partnerships.">
                        Travail en équipe et partenariats nationaux et internationaux.
                    </p>
                </div>
                <div class="feature">
                    <i class="fas fa-lightbulb"></i>
                    <h3 data-fr="Innovation" data-en="Innovation">Innovation</h3>
                    <p data-fr="Production de connaissances nouvelles et solutions innovantes."
                       data-en="Production of new knowledge and innovative solutions.">
                        Production de connaissances nouvelles et solutions innovantes.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
