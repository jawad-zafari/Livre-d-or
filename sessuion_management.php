<?php
// Configuration sécurisée des sessions
ini_set('session.cookie_secure', 1); // Uniquement via HTTPS
ini_set('session.cookie_httponly', 1); // Empêche l'accès via JavaScript
ini_set('session.use_strict_mode', 1); // Accepte uniquement les ID de session valides
ini_set('session.gc_maxlifetime', 1800); // Durée de vie de 30 minutes
ini_set('session.cookie_lifetime', 1800);

// Démarrer la session
session_start();

// Vérifier l'expiration de la session
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: connexion.php');
    exit;
}
$_SESSION['last_activity'] = time();

// Code spécifique à la page (lecture/écriture dans $_SESSION)

// Fermer la session
session_write_close();
?>