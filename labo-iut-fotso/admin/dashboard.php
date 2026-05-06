<?php
/**
 * DASHBOARD - Tableau de bord administrateur
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'auth_check.php';
require_once '../includes/db.php';

// Récupérer les statistiques générales
$stats = [];

// Nombre de chercheurs actifs
$stmt = $pdo->query("SELECT COUNT(*) FROM chercheurs WHERE statut = 'actif'");
$stats['chercheurs'] = $stmt->fetchColumn();

// Nombre de publications
$stmt = $pdo->query("SELECT COUNT(*) FROM publications WHERE visible = 1");
$stats['publications'] = $stmt->fetchColumn();

// Nombre de projets en cours
$stmt = $pdo->query("SELECT COUNT(*) FROM projets WHERE statut = 'En cours' AND visible = 1");
$stats['projets'] = $stmt->fetchColumn();

// Nombre d'événements à venir
$stmt = $pdo->query("SELECT COUNT(*) FROM evenements WHERE archive = 0 AND date_debut >= NOW()");
$stats['evenements'] = $stmt->fetchColumn();

// Nombre d'actualités publiées
$stmt = $pdo->query("SELECT COUNT(*) FROM actualites WHERE visible = 1");
$stats['actualites'] = $stmt->fetchColumn();

// Nombre de messages non lus
$stmt = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE lu = 0");
$stats['messages'] = $stmt->fetchColumn();

// Récupérer les 5 derniers messages non lus
$stmt = $pdo->query("SELECT * FROM messages_contact WHERE lu = 0 ORDER BY recu_le DESC LIMIT 5");
$messages_non_lus = $stmt->fetchAll();

// Récupérer les 3 prochains événements
$stmt = $pdo->query("SELECT * FROM evenements WHERE archive = 0 AND date_debut >= NOW() ORDER BY date_debut ASC LIMIT 3");
$prochains_evenements = $stmt->fetchAll();

$page_title = "Tableau de bord";
include '../includes/admin_header.php';
?>

<div class="dashboard-content">
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Tableau de bord</h1>
        <p>Bienvenue sur le panel d'administration, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="stats-grid">
        <div class="stat-card stat-chercheurs">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $stats['chercheurs']; ?></span>
                <span class="stat-label">Chercheurs actifs</span>
            </div>
            <a href="chercheurs.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card stat-publications">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $stats['publications']; ?></span>
                <span class="stat-label">Publications</span>
            </div>
            <a href="publications.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card stat-projets">
            <div class="stat-icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $stats['projets']; ?></span>
                <span class="stat-label">Projets en cours</span>
            </div>
            <a href="projets.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card stat-evenements">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $stats['evenements']; ?></span>
                <span class="stat-label">Événements à venir</span>
            </div>
            <a href="evenements.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card stat-actualites">
            <div class="stat-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $stats['actualites']; ?></span>
                <span class="stat-label">Actualités</span>
            </div>
            <a href="actualites.php" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="stat-card stat-messages">
            <div class="stat-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $stats['messages']; ?></span>
                <span class="stat-label">Messages non lus</span>
            </div>
            <a href="#messages" class="stat-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Messages non lus -->
        <div class="dashboard-section" id="messages">
            <div class="section-header">
                <h2><i class="fas fa-inbox"></i> Messages non lus</h2>
                <a href="#" class="btn btn-secondary btn-sm">Voir tous</a>
            </div>
            
            <?php if (empty($messages_non_lus)): ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>Aucun message non lu</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Expéditeur</th>
                                <th>Sujet</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages_non_lus as $msg): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($msg['prenom'] . ' ' . $msg['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['sujet']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($msg['recu_le'])); ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" onclick="alert('Fonctionnalité de lecture à implémenter')">
                                            <i class="fas fa-eye"></i> Lire
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Prochains événements -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-check"></i> Prochains événements</h2>
                <a href="evenements.php" class="btn btn-secondary btn-sm">Gérer</a>
            </div>
            
            <?php if (empty($prochains_evenements)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-plus"></i>
                    <p>Aucun événement à venir</p>
                    <a href="evenement_form.php" class="btn btn-primary btn-sm">Ajouter un événement</a>
                </div>
            <?php else: ?>
                <ul class="events-list">
                    <?php foreach ($prochains_evenements as $event): ?>
                        <li class="event-item">
                            <div class="event-date">
                                <span class="day"><?php echo date('d', strtotime($event['date_debut'])); ?></span>
                                <span class="month"><?php echo date('M', strtotime($event['date_debut'])); ?></span>
                            </div>
                            <div class="event-info">
                                <h4><?php echo htmlspecialchars($event['titre']); ?></h4>
                                <p>
                                    <i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($event['date_debut'])); ?>
                                    <?php if ($event['lieu']): ?>
                                        | <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['lieu']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="event-actions">
                                <a href="evenement_form.php?id=<?php echo $event['id']; ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="quick-links">
        <h2><i class="fas fa-link"></i> Accès rapide</h2>
        <div class="quick-links-grid">
            <a href="chercheurs.php" class="quick-link">
                <i class="fas fa-users"></i>
                <span>Gérer les chercheurs</span>
            </a>
            <a href="publications.php" class="quick-link">
                <i class="fas fa-book"></i>
                <span>Gérer les publications</span>
            </a>
            <a href="projets.php" class="quick-link">
                <i class="fas fa-project-diagram"></i>
                <span>Gérer les projets</span>
            </a>
            <a href="axes.php" class="quick-link">
                <i class="fas fa-flask"></i>
                <span>Gérer les axes</span>
            </a>
            <a href="partenaires.php" class="quick-link">
                <i class="fas fa-handshake"></i>
                <span>Gérer les partenaires</span>
            </a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="utilisateurs.php" class="quick-link">
                <i class="fas fa-user-shield"></i>
                <span>Gérer les utilisateurs</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>
