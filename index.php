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
session_write_close(); // Fermer la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Livre d'Or</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="Logo" alt="Livre d'or">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="livre-or.php">Livre d'Or</a></li>
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
        <h1>Bienvenue sur Livre d'Or</h1>
        <section class="project-description">
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80" alt="Illustration du développement" class="project-img">
            <h2>Partagez vos expériences et laissez vos avis sur notre site !</h2>
            <h2>Créez un livre d'or unique avec nous, où chaque commentaire compte. Rejoignez notre communauté dès aujourd'hui.</h2>
            <h2><p>Nous avons conçu ce projet pour permettre aux utilisateurs de laisser leurs avis sur notre site.</p></h2>
            <h3>Pour commencer :</h3>
            <ul>
                <li>Inscrivez-vous et connectez-vous facilement.</li>
                <li>Modifiez votre profil à tout moment.</li>
                <li>Partagez vos commentaires, visibles du plus récent au plus ancien.</li>
            </ul>
        </section>
        <a href="https://github.com/prenom-nom/livre-or" target="_blank" class="github-link">Voir sur GitHub</a>
    </main>
    <footer>
        <p>&copy; 2025 Livre d'Or</p>
    </footer>
</body>
</html>