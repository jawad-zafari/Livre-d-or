
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

// Inclure la configuration de la base de données
require 'config.php';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Tous les champs sont requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "L'email est invalide.";
    } else {
        // Vérifier l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT id, password FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_unset(); // Supprimer les données de session précédentes
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['last_activity'] = time();
            session_write_close(); // Fermer la session
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
        }
    }
}
session_write_close(); // Fermer la session après traitement
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Livre d'Or</title>
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
                <li><a href="inscription.php">Inscription</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Connexion</h1>
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
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Livre d'Or</p>
    </footer>
</body>
</html>