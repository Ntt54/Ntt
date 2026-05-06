<?php
/**
 * LOGIN - Page de connexion administrateur
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

session_start();

// Si déjà connecté, rediriger vers le dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once '../includes/db.php';

$error_message = '';
$login_attempts = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] : 0;
$lockout_time = isset($_SESSION['lockout_time']) ? $_SESSION['lockout_time'] : 0;

// Vérifier si le compte est temporairement bloqué
if ($lockout_time > time()) {
    $wait_time = ceil(($lockout_time - time()) / 60);
    $error_message = "Trop de tentatives échouées. Veuillez attendre $wait_time minute(s).";
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error_message)) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error_message = "Veuillez remplir tous les champs.";
    } else {
        try {
            // Récupérer l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = ? AND actif = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                // Connexion réussie
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Réinitialiser les tentatives
                unset($_SESSION['login_attempts']);
                unset($_SESSION['lockout_time']);
                
                header('Location: dashboard.php');
                exit;
            } else {
                // Échec de connexion
                $login_attempts++;
                $_SESSION['login_attempts'] = $login_attempts;
                
                if ($login_attempts >= 5) {
                    $_SESSION['lockout_time'] = time() + (5 * 60); // Bloquer pendant 5 minutes
                    $error_message = "Trop de tentatives échouées. Compte bloqué pendant 5 minutes.";
                } else {
                    $error_message = "Identifiants incorrects. Tentative " . $login_attempts . "/5";
                }
            }
        } catch (PDOException $e) {
            $error_message = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}

$page_title = "Connexion Admin";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Laboratoire IUT Fotso Victor</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg'><text y='32' font-size='32'>🔬</text></svg>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-microscope"></i>
                </div>
                <h1>Laboratoire de Recherche</h1>
                <p>IUT Fotso Victor de Bandjoun</p>
                <h2>Administration</h2>
            </div>
            
            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form" id="login-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Nom d'utilisateur
                    </label>
                    <input type="text" id="username" name="username" required 
                           autocomplete="username" autofocus
                           placeholder="Votre nom d'utilisateur">
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Mot de passe
                    </label>
                    <div class="password-input">
                        <input type="password" id="password" name="password" required 
                               autocomplete="current-password"
                               placeholder="Votre mot de passe">
                        <button type="button" class="toggle-password" aria-label="Afficher/masquer le mot de passe">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </button>
            </form>
            
            <div class="login-footer">
                <p><a href="../index.php"><i class="fas fa-arrow-left"></i> Retour au site</a></p>
                <p class="help-text">Identifiants par défaut : admin / Admin@2025</p>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
