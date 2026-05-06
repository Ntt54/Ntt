<?php
/**
 * CONTACT - Formulaire de contact et informations
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

require_once 'includes/db.php';
require_once 'includes/functions.php';

$message_success = '';
$message_error = '';

// Traitement du formulaire de contact
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    // Vérification du token CSRF
    if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
        $message_error = "Erreur de sécurité. Veuillez réessayer.";
    } else {
        // Récupération et validation des données
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $sujet = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        $errors = [];
        
        if (empty($nom)) $errors[] = "Le nom est requis.";
        if (empty($prenom)) $errors[] = "Le prénom est requis.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide.";
        if (empty($sujet)) $errors[] = "Le sujet est requis.";
        if (empty($message) || strlen($message) < 20) $errors[] = "Le message doit contenir au moins 20 caractères.";
        
        if (empty($errors)) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO messages_contact (nom, prenom, email, sujet, message)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$nom, $prenom, $email, $sujet, $message]);
                $message_success = "Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.";
                
                // Réinitialiser le formulaire
                $_POST = array();
            } catch (PDOException $e) {
                $message_error = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
            }
        } else {
            $message_error = implode(" ", $errors);
        }
    }
}

// Traitement de l'inscription à la newsletter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'newsletter') {
    $email = trim($_POST['email'] ?? '');
    
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $token = bin2hex(random_bytes(32));
            $stmt = $pdo->prepare("
                INSERT INTO newsletter (email, token)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE actif = 1, inscrit_le = CURRENT_TIMESTAMP
            ");
            $stmt->execute([$email, $token]);
            $message_success = "Vous avez été inscrit avec succès à notre newsletter.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message_error = "Cette adresse email est déjà inscrite à la newsletter.";
            } else {
                $message_error = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    } else {
        $message_error = "Veuillez entrer une adresse email valide.";
    }
}

// Générer un nouveau token CSRF
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$page_title = "Contact";
include 'includes/header.php';
?>

<main class="page-content">
    <section class="hero-section hero-sm">
        <div class="container">
            <h1 data-fr="Contactez-nous" data-en="Contact us">Contactez-nous</h1>
            <p data-fr="Une question ? Un projet ? N'hésitez pas à nous écrire" 
               data-en="A question? A project? Do not hesitate to write to us">
                Une question ? Un projet ? N'hésitez pas à nous écrire
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <?php if ($message_success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($message_success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($message_error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($message_error); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-2 contact-grid">
                <!-- Formulaire de contact -->
                <div class="contact-form-container">
                    <h2 data-fr="Envoyez-nous un message" data-en="Send us a message">Envoyez-nous un message</h2>
                    
                    <form action="contact.php" method="POST" class="contact-form" id="contact-form" novalidate>
                        <input type="hidden" name="action" value="contact">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nom" data-fr="Nom *" data-en="Last name *">Nom *</label>
                                <input type="text" id="nom" name="nom" required 
                                       value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"
                                       placeholder="Votre nom"
                                       data-fr-placeholder="Votre nom"
                                       data-en-placeholder="Your last name">
                                <span class="error-message"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="prenom" data-fr="Prénom *" data-en="First name *">Prénom *</label>
                                <input type="text" id="prenom" name="prenom" required 
                                       value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>"
                                       placeholder="Votre prénom"
                                       data-fr-placeholder="Votre prénom"
                                       data-en-placeholder="Your first name">
                                <span class="error-message"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" data-fr="Email *" data-en="Email *">Email *</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   placeholder="votre@email.com"
                                   data-fr-placeholder="votre@email.com"
                                   data-en-placeholder="your@email.com">
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujet" data-fr="Sujet *" data-en="Subject *">Sujet *</label>
                            <input type="text" id="sujet" name="sujet" required 
                                   value="<?php echo htmlspecialchars($_POST['sujet'] ?? ''); ?>"
                                   placeholder="Sujet de votre message"
                                   data-fr-placeholder="Sujet de votre message"
                                   data-en-placeholder="Subject of your message">
                            <span class="error-message"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="message" data-fr="Message *" data-en="Message *">Message *</label>
                            <textarea id="message" name="message" rows="6" required 
                                      placeholder="Votre message (minimum 20 caractères)"
                                      data-fr-placeholder="Votre message (minimum 20 caractères)"
                                      data-en-placeholder="Your message (minimum 20 characters)"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            <span class="error-message"></span>
                            <span class="char-count"><span id="char-current">0</span>/20</span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i>
                            <span data-fr="Envoyer le message" data-en="Send message">Envoyer le message</span>
                        </button>
                    </form>
                </div>

                <!-- Informations de contact -->
                <div class="contact-info-container">
                    <h2 data-fr="Nos coordonnées" data-en="Our coordinates">Nos coordonnées</h2>
                    
                    <div class="contact-info">
                        <div class="info-item">
                            <div class="icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="content">
                                <h3 data-fr="Adresse" data-en="Address">Adresse</h3>
                                <p>IUT Fotso Victor de Bandjoun<br>
                                   Route de Bafoussam<br>
                                   Bandjoun, Cameroun</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="content">
                                <h3 data-fr="Email" data-en="Email">Email</h3>
                                <a href="mailto:labo.recherche@iutfv.cm">labo.recherche@iutfv.cm</a>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="content">
                                <h3 data-fr="Téléphone" data-en="Phone">Téléphone</h3>
                                <a href="tel:+237699000000">+237 699 000 000</a>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="content">
                                <h3 data-fr="Horaires" data-en="Opening hours">Horaires</h3>
                                <p>Lundi - Vendredi : 8h00 - 17h00<br>
                                   Samedi : 9h00 - 12h00</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Réseaux sociaux -->
                    <div class="social-links">
                        <h3 data-fr="Suivez-nous" data-en="Follow us">Suivez-nous</h3>
                        <div class="social-icons">
                            <a href="#" aria-label="Twitter/X" class="social-link twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" aria-label="LinkedIn" class="social-link linkedin">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" aria-label="YouTube" class="social-link youtube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" aria-label="Facebook" class="social-link facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Google Maps -->
    <section class="section section-alt">
        <div class="container">
            <h2 data-fr="Nous localiser" data-en="Locate us">Nous localiser</h2>
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.476742989076!2d10.4167!3d5.5167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1061b0c8c0c0c0c1%3A0x1234567890abcdef!2sBandjoun%2C%20Cameroun!5e0!3m2!1sfr!2scm!4v1234567890" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Carte de localisation du laboratoire">
                </iframe>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
