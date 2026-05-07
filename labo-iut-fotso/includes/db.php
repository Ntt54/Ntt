
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Connexion PDO à MySQL pour XAMPP
 * Laboratoire de Recherche - IUT Fotso Victor
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'labo_iutfv');
define('DB_USER', 'root');          // utilisateur XAMPP par défaut
define('DB_PASS', 'root');              // mot de passe vide par défaut sur XAMPP
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die(json_encode(['erreur' => 'Connexion échouée : ' . $e->getMessage()]));
}
