
<?php
/**
 * PAGE 404 - Page d'erreur personnalisée
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

$page_title = "Page introuvable - Erreur 404";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="error-page">
        <div class="container">
            <div class="error-content">
                <div class="error-code">
                    <span class="code-4">4</span>
                    <i class="fas fa-microscope"></i>
                    <span class="code-0">0</span>
                    <span class="code-4">4</span>
                </div>

                <h1 data-fr="Page introuvable" data-en="Page not found">Page introuvable</h1>
                <p data-fr="Désolé, la page que vous recherchez n'existe pas ou a été déplacée."
                   data-en="Sorry, the page you are looking for does not exist or has been moved.">
                    Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
                </p>

                <div class="error-actions">
                    <a href="index.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i>
                        <span data-fr="Retour à l'accueil" data-en="Back to home">Retour à l'accueil</span>
                    </a>
                    <a href="contact.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-envelope"></i>
                        <span data-fr="Nous contacter" data-en="Contact us">Nous contacter</span>
                    </a>
                </div>

                <!-- Suggestions de pages -->
                <div class="error-suggestions">
                    <h3 data-fr="Pages populaires" data-en="Popular pages">Pages populaires</h3>
                    <ul>
                        <li><a href="about.php"><i class="fas fa-angle-right"></i> <span data-fr="À propos" data-en="About">À propos</span></a></li>
                        <li><a href="equipe.php"><i class="fas fa-angle-right"></i> <span data-fr="Équipe" data-en="Team">Équipe</span></a></li>
                        <li><a href="axes.php"><i class="fas fa-angle-right"></i> <span data-fr="Axes de recherche" data-en="Research areas">Axes de recherche</span></a></li>
                        <li><a href="publications.php"><i class="fas fa-angle-right"></i> <span data-fr="Publications" data-en="Publications">Publications</span></a></li>
                        <li><a href="projets.php"><i class="fas fa-angle-right"></i> <span data-fr="Projets" data-en="Projects">Projets</span></a></li>
                        <li><a href="evenements.php"><i class="fas fa-angle-right"></i> <span data-fr="Événements" data-en="Events">Événements</span></a></li>
                        <li><a href="actualites.php"><i class="fas fa-angle-right"></i> <span data-fr="Actualités" data-en="News">Actualités</span></a></li>
                        <li><a href="contact.php"><i class="fas fa-angle-right"></i> <span data-fr="Contact" data-en="Contact">Contact</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
.error-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    background: linear-gradient(135deg, var(--blanc-casse) 0%, #e9ecef 100%);
}

.error-content {
    text-align: center;
    max-width: 700px;
}

.error-code {
    font-size: 120px;
    font-weight: 700;
    color: var(--bleu-fonce);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.error-code i {
    font-size: 80px;
    color: var(--vert-labo);
}

.error-content h1 {
    font-size: 36px;
    color: var(--bleu-fonce);
    margin-bottom: 15px;
}

.error-content > p {
    font-size: 18px;
    color: var(--gris-texte);
    margin-bottom: 30px;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 40px;
}

.error-suggestions {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.error-suggestions h3 {
    color: var(--bleu-fonce);
    margin-bottom: 20px;
    font-size: 20px;
}

.error-suggestions ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
}

.error-suggestions li a {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--bleu-lien);
    text-decoration: none;
    padding: 10px 15px;
    background: var(--blanc-casse);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.error-suggestions li a:hover {
    background: var(--bleu-fonce);
    color: white;
    transform: translateX(5px);
}

.error-suggestions li a i {
    font-size: 14px;
}

@media (max-width: 768px) {
    .error-code {
        font-size: 80px;
    }

    .error-code i {
        font-size: 50px;
    }

    .error-content h1 {
        font-size: 28px;
    }

    .error-actions {
        flex-direction: column;
        align-items: center;
    }

    .error-suggestions ul {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
