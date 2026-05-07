
<?php
/**
 * PARTENAIRES - Liste des partenaires du laboratoire
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

// Récupérer les partenaires nationaux
$stmtNational = $pdo->prepare("SELECT * FROM partenaires WHERE type = 'National' ORDER BY nom ASC");
$stmtNational->execute();
$partenaires_national = $stmtNational->fetchAll();

// Récupérer les partenaires internationaux
$stmtInternational = $pdo->prepare("SELECT * FROM partenaires WHERE type = 'International' ORDER BY nom ASC");
$stmtInternational->execute();
$partenaires_international = $stmtInternational->fetchAll();

$page_title = "Partenaires";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Nos Partenaires" data-en="Our Partners">Nos Partenaires</h1>
            <p data-fr="Les institutions qui soutiennent et collaborent avec notre laboratoire"
               data-en="Institutions that support and collaborate with our laboratory">
                Les institutions qui soutiennent et collaborent avec notre laboratoire
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <!-- Partenaires Nationaux -->
            <h2 class="section-title">
                <i class="fas fa-flag"></i>
                <span data-fr="Partenaires Nationaux" data-en="National Partners">Partenaires Nationaux</span>
            </h2>

            <?php if (empty($partenaires_national)): ?>
                <p class="empty-message" data-fr="Aucun partenaire national répertorié." data-en="No national partner listed.">
                    Aucun partenaire national répertorié.
                </p>
            <?php else: ?>
                <div class="grid grid-3">
                    <?php foreach ($partenaires_national as $partenaire): ?>
                        <div class="card partenaire-card">
                            <div class="partenaire-logo">
                                <?php if ($partenaire['logo'] && file_exists($partenaire['logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($partenaire['logo']); ?>"
                                         alt="Logo de <?php echo htmlspecialchars($partenaire['nom']); ?>">
                                <?php else: ?>
                                    <div class="logo-placeholder">
                                        <span><?php echo strtoupper(substr($partenaire['nom'], 0, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="partenaire-info">
                                <h3><?php echo htmlspecialchars($partenaire['nom']); ?></h3>
                                <?php if ($partenaire['pays']): ?>
                                    <p class="pays">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($partenaire['pays']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($partenaire['description']): ?>
                                    <p class="description"><?php echo truncateText(htmlspecialchars($partenaire['description']), 100); ?></p>
                                <?php endif; ?>

                                <?php if ($partenaire['site_web']): ?>
                                    <a href="<?php echo htmlspecialchars($partenaire['site_web']); ?>"
                                       target="_blank"
                                       rel="noopener"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                        <span data-fr="Visiter le site" data-en="Visit website">Visiter le site</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <!-- Partenaires Internationaux -->
            <h2 class="section-title">
                <i class="fas fa-globe"></i>
                <span data-fr="Partenaires Internationaux" data-en="International Partners">Partenaires Internationaux</span>
            </h2>

            <?php if (empty($partenaires_international)): ?>
                <p class="empty-message" data-fr="Aucun partenaire international répertorié." data-en="No international partner listed.">
                    Aucun partenaire international répertorié.
                </p>
            <?php else: ?>
                <div class="grid grid-3">
                    <?php foreach ($partenaires_international as $partenaire): ?>
                        <div class="card partenaire-card">
                            <div class="partenaire-logo">
                                <?php if ($partenaire['logo'] && file_exists($partenaire['logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($partenaire['logo']); ?>"
                                         alt="Logo de <?php echo htmlspecialchars($partenaire['nom']); ?>">
                                <?php else: ?>
                                    <div class="logo-placeholder">
                                        <span><?php echo strtoupper(substr($partenaire['nom'], 0, 2)); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="partenaire-info">
                                <h3><?php echo htmlspecialchars($partenaire['nom']); ?></h3>
                                <?php if ($partenaire['pays']): ?>
                                    <p class="pays">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($partenaire['pays']); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($partenaire['description']): ?>
                                    <p class="description"><?php echo truncateText(htmlspecialchars($partenaire['description']), 100); ?></p>
                                <?php endif; ?>

                                <?php if ($partenaire['site_web']): ?>
                                    <a href="<?php echo htmlspecialchars($partenaire['site_web']); ?>"
                                       target="_blank"
                                       rel="noopener"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                        <span data-fr="Visiter le site" data-en="Visit website">Visiter le site</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Section devenir partenaire -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-box">
                <h2 data-fr="Devenir partenaire" data-en="Become a partner">Devenir partenaire</h2>
                <p data-fr="Vous souhaitez collaborer avec notre laboratoire ? Découvrez les opportunités de partenariat."
                   data-en="You want to collaborate with our laboratory? Discover partnership opportunities.">
                    Vous souhaitez collaborer avec notre laboratoire ? Découvrez les opportunités de partenariat.
                </p>
                <a href="contact.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-handshake"></i>
                    <span data-fr="Nous contacter" data-en="Contact us">Nous contacter</span>
                </a>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
