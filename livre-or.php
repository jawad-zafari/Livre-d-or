<?php
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
$is_logged_in = isset($_SESSION['user_id']);

// Inclure la configuration de la base de données
require 'config.php';

// Récupérer les commentaires
$stmt = $pdo->query("SELECT c.*, u.email FROM commentaires c JOIN utilisateurs u ON c.id_utilisateur = u.id ORDER BY c.date DESC");
$comments = $stmt->fetchAll();
session_write_close(); // Fermer la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'Or</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="" alt="Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <?php if ($is_logged_in) { ?>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="commentaire.php">Ajouter</a></li>
                    <li><a href="logout.php">Déconnexion</a></li>
                <?php } else { ?>
                    <li><a href="connexion.php">Connexion</a></li>
                    <li><a href="inscription.php">Inscription</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Livre d'Or</h1>
        <?php if ($is_logged_in) { ?>
            <a href="commentaire.php" class="add-comment">Ajouter un commentaire</a>
        <?php } ?>
        <?php foreach ($comments as $comment) { ?>
            <div class="comment">
                <p class="user">Posté le <?php echo date('d/m/Y à H:i', strtotime($comment['date'])); ?> par <?php echo htmlspecialchars($comment['email']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($comment['commentaire'])); ?></p>
            </div>
        <?php } ?>
    </main>
    <footer>
        <p>&copy; 2025 Livre d'Or</p>
    </footer>
</body>
</html>