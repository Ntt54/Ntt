<?php
/**
 * LOGIN - Page de connexion administrateur
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */
session_start();

// Si l'utilisateur est déjà connecté, redirection vers le tableau de bord
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
$success_message = '';

// Vérification du message de déconnexion
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $success_message = "Vous avez été déconnecté avec succès.";
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = "Veuillez remplir tous les champs.";
    } elseif ($username === 'admin' && $password === 'Admin@2025') {
        // Connexion réussie
        session_regenerate_id(true); // Sécurité : évite la fixation de session

        $_SESSION['user_id']   = 1;
        $_SESSION['username']  = 'admin';
        $_SESSION['email']     = 'admin@labo-iut.com'; // Valeur par défaut
        $_SESSION['role']      = 'admin';

        header('Location: dashboard.php');
        exit;
    } else {
        // Échec de connexion
        $error_message = "Identifiants incorrects.";
    }
}

$page_title = "Connexion Admin";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <!-- Ajoutez ici vos liens CSS habituels -->
</head>
<body>
    <div class="login-container" style="max-width: 400px; margin: 80px auto; padding: 25px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div class="login-header" style="text-align: center; margin-bottom: 20px;">
            <h2>Laboratoire de Recherche</h2>
            <h3>IUT Fotso Victor de Bandjoun</h3>
            <h4>Administration</h4>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error" style="color: #721c24; background: #f8d7da; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" style="color: #155724; background: #d4edda; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required autocomplete="username" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <button type="submit" style="width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Se connecter</button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 14px;">
            <a href="../index.php">← Retour au site</a><br>
            <small style="color: #666;">Identifiants par défaut : admin / Admin@2025</small>
        </p>
    </div>
</body>
</html>
