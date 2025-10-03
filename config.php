<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=livreor', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Activer les exceptions pour les erreurs
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Retourner les résultats sous forme de tableau associatif
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>