<?php
/**
 * AUTH_CHECK - Middleware de vérification d'authentification
 * À inclure en haut de chaque page admin protégée
 * Laboratoire de Recherche - IUT Fotso Victor de Bandjoun
 */

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

// Fonction pour vérifier le rôle admin
function requireAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        $_SESSION['error_message'] = "Accès réservé aux administrateurs uniquement.";
        header('Location: dashboard.php');
        exit;
    }
}

// Régénérer le token CSRF s'il n'existe pas
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Vérifier le token CSRF pour les requêtes POST
function verifyCSRF($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}
