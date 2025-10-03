<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    session_write_close();
    header('Location: connexion.php');
    exit;
}

// Vérifier l'expiration de la session
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: connexion.php');
    exit;
}
$_SESSION['last_activity'] = time();

// Inclure la configuration de la base de données
require 'config.php';

// Traitement du formulaire de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentaire = trim($_POST['commentaire']);
    if (!empty($commentaire)) {
        // Insérer le commentaire dans la base de données
        $stmt = $pdo->prepare("INSERT INTO commentaires (commentaire, id_utilisateur, date) VALUES (?, ?, NOW())");
        $stmt->execute([$commentaire, $_SESSION['user_id']]);
        session_write_close();
        header('Location: livre-or.php');
        exit;
    } else {
        $_SESSION['error'] = "Le commentaire ne peut pas être vide.";
    }
}
session_write_close(); // Fermer la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Commentaire - Livre d'Or</title>
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
                <li><a href="livre-or.php">Livre d'Or</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Ajouter un Commentaire</h1>
        <?php
        // Afficher les messages d'erreur
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
            session_write_close();
        }
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="commentaire">Commentaire</label>
                <textarea id="commentaire" name="commentaire" required></textarea>
            </div>
            <button type="submit">Envoyer</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Livre d'Or</p>
    </footer>
</body>
</html>