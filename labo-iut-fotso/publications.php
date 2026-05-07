
<?php
/**
 * PUBLICATIONS - Liste des publications scientifiques
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer les paramètres de filtre
$axe_filter = isset($_GET['axe']) ? (int)$_GET['axe'] : null;
$type_filter = isset($_GET['type']) ? $_GET['type'] : null;
$year_filter = isset($_GET['annee']) ? (int)$_GET['annee'] : null;

// Construire la requête avec filtres
$sql = "
    SELECT p.*, a.titre as axe_titre,
           GROUP_CONCAT(CONCAT(c.nom, ' ', c.prenom) SEPARATOR ', ') as auteurs
    FROM publications p
    LEFT JOIN axes_recherche a ON p.axe_id = a.id
    LEFT JOIN publication_auteurs pa ON p.id = pa.publication_id
    LEFT JOIN chercheurs c ON pa.chercheur_id = c.id
    WHERE p.visible = 1
";

$params = [];

if ($axe_filter) {
    $sql .= " AND p.axe_id = ?";
    $params[] = $axe_filter;
}

if ($type_filter) {
    $sql .= " AND p.type = ?";
    $params[] = $type_filter;
}

if ($year_filter) {
    $sql .= " AND p.annee = ?";
    $params[] = $year_filter;
}

$sql .= " GROUP BY p.id ORDER BY p.annee DESC, p.titre ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$publications = $stmt->fetchAll();

// Récupérer les types uniques pour les filtres
$types = ['Article', 'Thèse', 'Conférence', 'Rapport'];

// Récupérer les années disponibles
$yearStmt = $pdo->query("SELECT DISTINCT annee FROM publications WHERE visible = 1 ORDER BY annee DESC");
$years = $yearStmt->fetchAll(PDO::FETCH_COLUMN);

// Récupérer les axes pour le filtre
$axesStmt = $pdo->query("SELECT id, titre FROM axes_recherche ORDER BY titre");
$axes = $axesStmt->fetchAll();

$page_title = "Publications Scientifiques";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Publications Scientifiques" data-en="Scientific Publications">Publications Scientifiques</h1>
            <p data-fr="Découvrez nos travaux de recherche publiés" data-en="Discover our published research work">
                Découvrez nos travaux de recherche publiés
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Barre de filtres -->
            <div class="filter-bar filter-complex">
                <div class="filter-group">
                    <span data-fr="Type:" data-en="Type:">Type:</span>
                    <button class="filter-btn active" data-filter-type="all" data-fr="Tous" data-en="All">Tous</button>
                    <?php foreach ($types as $type): ?>
                        <button class="filter-btn" data-filter-type="<?php echo htmlspecialchars($type); ?>">
                            <?php echo htmlspecialchars($type); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div class="filter-group">
                    <span data-fr="Année:" data-en="Year:">Année:</span>
                    <select id="year-filter" class="filter-select">
                        <option value="all" data-fr="Toutes" data-en="All">Toutes</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if (!empty($axes)): ?>
                <div class="filter-group">
                    <span data-fr="Axe:" data-en="Area:">Axe:</span>
                    <select id="axe-filter" class="filter-select">
                        <option value="all" data-fr="Tous" data-en="All">Tous</option>
                        <?php foreach ($axes as $axe): ?>
                            <option value="<?php echo $axe['id']; ?>"><?php echo htmlspecialchars($axe['titre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <input type="text" id="search-input" class="search-input"
                       placeholder="Rechercher..."
                       data-fr-placeholder="Rechercher..."
                       data-en-placeholder="Search...">
            </div>

            <!-- Liste des publications -->
            <div class="publications-list-container" id="publications-list">
                <?php if (empty($publications)): ?>
                    <p class="empty-message" data-fr="Aucune publication disponible pour ce filtre." data-en="No publication available for this filter.">
                        Aucune publication disponible pour ce filtre.
                    </p>
                <?php else: ?>
                    <?php foreach ($publications as $pub): ?>
                        <div class="card publication-card"
                             data-type="<?php echo htmlspecialchars($pub['type']); ?>"
                             data-annee="<?php echo $pub['annee']; ?>"
                             data-axe="<?php echo $pub['axe_id']; ?>"
                             data-titre="<?php echo htmlspecialchars(strtolower($pub['titre'])); ?>">

                            <div class="publication-header">
                                <span class="badge badge-<?php echo getTypeBadgeClass($pub['type']); ?>">
                                    <?php echo htmlspecialchars($pub['type']); ?>
                                </span>
                                <span class="badge badge-lang"><?php echo htmlspecialchars($pub['langue']); ?></span>
                                <?php if ($pub['annee']): ?>
                                    <span class="year"><?php echo $pub['annee']; ?></span>
                                <?php endif; ?>
                            </div>

                            <h3><?php echo htmlspecialchars($pub['titre']); ?></h3>

                            <?php if ($pub['auteurs']): ?>
                                <p class="auteurs">
                                    <i class="fas fa-users"></i>
                                    <?php echo htmlspecialchars($pub['auteurs']); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($pub['axe_titre']): ?>
                                <p class="axe">
                                    <i class="fas fa-flask"></i>
                                    <?php echo htmlspecialchars($pub['axe_titre']); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($pub['resume']): ?>
                                <p class="resume"><?php echo truncateText(htmlspecialchars($pub['resume']), 200); ?></p>
                            <?php endif; ?>

                            <?php if ($pub['mots_cles']): ?>
                                <div class="keywords">
                                    <?php
                                    $mots = explode(',', $pub['mots_cles']);
                                    foreach ($mots as $mot):
                                        if (trim($mot)):
                                    ?>
                                        <span class="keyword"><?php echo htmlspecialchars(trim($mot)); ?></span>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </div>
                            <?php endif; ?>

                            <div class="publication-actions">
                                <?php if ($pub['fichier_pdf'] && file_exists($pub['fichier_pdf'])): ?>
                                    <a href="<?php echo htmlspecialchars($pub['fichier_pdf']); ?>"
                                       download
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-download"></i>
                                        <span data-fr="Télécharger PDF" data-en="Download PDF">Télécharger PDF</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($pub['lien_externe']): ?>
                                    <a href="<?php echo htmlspecialchars($pub['lien_externe']); ?>"
                                       target="_blank"
                                       rel="noopener"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                        <span data-fr="Voir en ligne" data-en="View online">Voir en ligne</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
