
<?php
/**
 * Fonctions utilitaires PHP réutilisables
 * Laboratoire de Recherche - IUT Fotso Victor
 */

/**
 * Échappe les caractères HTML pour affichage sécurisé
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un token CSRF pour la protection des formulaires
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirection sécurisée
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Formatage de date en français
 */
function formatDateFr($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    return strftime('%d %B %Y', $timestamp);
}

/**
 * Formatage de datetime en français
 */
function formatDateTimeFr($datetime) {
    if (empty($datetime)) return '';
    $timestamp = strtotime($datetime);
    setlocale(LC_TIME, 'fr_FR.UTF-8');
    return strftime('%d %B %Y à %H:%M', $timestamp);
}

/**
 * Extrait de texte avec limite de caractères
 */
function excerpt($text, $length = 150) {
    if (strlen($text) <= $length) return e($text);
    return e(substr($text, 0, $length)) . '...';
}

/**
 * Upload de fichier sécurisé
 */
function uploadFile($file, $directory, $allowedTypes = [], $maxSize = 2097152) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Erreur lors de l\'upload'];
    }

    // Vérifier la taille
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Fichier trop volumieux (max 2Mo)'];
    }

    // Vérifier le type MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Type de fichier non autorisé'];
    }

    // Générer un nom unique
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = uniqid() . '.' . $extension;
    $destination = $directory . '/' . $newName;

    // Créer le dossier s'il n'existe pas
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    // Déplacer le fichier
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newName];
    }

    return ['success' => false, 'message' => 'Échec de l\'upload'];
}

/**
 * Récupère le badge coloré selon le grade
 */
function getGradeBadgeClass($grade) {
    $classes = [
        'Professeur' => 'badge-purple',
        'MCF' => 'badge-blue',
        'Doctorant' => 'badge-green',
        'Ingénieur' => 'badge-orange',
        'Post-doc' => 'badge-pink'
    ];
    return $classes[$grade] ?? 'badge-gray';
}

/**
 * Récupère le badge coloré selon le statut d'un projet
 */
function getProjetStatutBadgeClass($statut) {
    $classes = [
        'En cours' => 'badge-success',
        'Terminé' => 'badge-secondary',
        'À venir' => 'badge-info'
    ];
    return $classes[$statut] ?? 'badge-gray';
}

/**
 * Calcule la différence entre deux dates
 */
function dateDiff($date1, $date2) {
    $interval = date_diff(new DateTime($date1), new DateTime($date2));
    return $interval->days;
}

/**
 * Vérifie si une date est dans le futur
 */
function isFutureDate($date) {
    return strtotime($date) > time();
}
