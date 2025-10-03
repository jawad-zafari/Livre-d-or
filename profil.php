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
$user_id = $_SESSION['user_id'];

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = trim($_POST['email']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    
    $has_error = false;

    // Vérification de l'email
    if (!empty($new_email)) {
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "L'email est invalide.";
            $has_error = true;
        } else {
            // Vérifier si l'email est déjà utilisé
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
            $stmt->execute([$new_email, $user_id]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = "Cet email est déjà utilisé.";
                $has_error = true;
            }
        }
    }

    // Vérification du mot de passe
    if (!empty($new_password)) {
        if (empty($confirm_password)) {
            $_SESSION['error'] = "Veuillez confirmer le mot de passe.";
            $has_error = true;
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            $has_error = true;
        }
    } elseif (!empty($confirm_password)) {
        $_SESSION['error'] = "Veuillez entrer un nouveau mot de passe.";
        $has_error = true;
    }

    // Si aucune erreur, procéder aux mises à jour
    if (!$has_error) {
        $updated = false;

        if (!empty($new_email)) {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET email = ? WHERE id = ?");
            $stmt->execute([$new_email, $user_id]);
            $updated = true;
        }

        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
            $updated = true;
        }

        if ($updated) {
            $_SESSION['success'] = "Profil mis à jour avec succès.";
        }
    }
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
session_write_close(); // Fermer la session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Livre d'Or</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="Plateforme" alt="Logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="livre-or.php">Livre d'Or</a></li>
                <li><a href="commentaire.php">Ajouter</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Modifier Profil</h1>
        <?php
        // Afficher les messages d'erreur ou de succès
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
            session_write_close();
        }
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>" . htmlspecialchars($_SESSION['success']) . "</p>";
            unset($_SESSION['success']);
            session_write_close();
        }
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="email">Nouvel Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
            <div class="form-group">
                <label for="password">Nouveau Mot de passe</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            <button type="submit">Mettre à jour</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Livre d'Or</p>
    </footer>
</body>
</html>