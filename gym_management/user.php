<?php
session_start();
require_once "config.php"; // Connexion à la base de données

// Vérification de l'accès (seul le coach peut créer un utilisateur)
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "coach") {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $role = trim($_POST["role"]);
    $pin_code = trim($_POST["pin_code"]);

    if (empty($nom) || empty($role) || empty($pin_code)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, role, pin_code) VALUES (?, ?, ?)");
        $result = $stmt->execute([$nom, $role, $pin_code]);

        if ($result) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Erreur lors de la création de l'utilisateur.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Important pour la responsivité -->
    <title>Créer un Utilisateur</title>
    <style>
        /* Styles globaux modernes */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background: #f0f2f5; font-size: 16px; } /* Taille de police par défaut pour les écrans plus larges */
        header { text-align: center; padding: 20px; background: #007BFF; color: white; font-size: 24px; }
        nav { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; padding: 10px; }
        nav a { margin: 0 10px; text-decoration: none; color: #007BFF; font-weight: bold; transition: color 0.3s; font-size: 18px; }
        nav a:hover { color: #0056b3; }
        main { padding: 20px; max-width: 600px; margin: 20px auto; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; }
        form { display: flex; flex-direction: column; }
        form label { margin-bottom: 10px; font-weight: bold; font-size: 18px; }
        form input, form select { padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px; }
        form button { padding: 12px; background: #007BFF; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; transition: background 0.3s; }
        form button:hover { background: #0056b3; }
        .error { color: red; text-align: center; margin-bottom: 10px; font-size: 16px; }
        footer { text-align: center; padding: 10px; background: #007BFF; color: white; position: fixed; bottom: 0; width: 100%; }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                font-size: 18px; /* Augmente la taille de la police sur mobile */
            }
            header h1 {
                font-size: 28px; /* Taille du titre plus grande sur mobile */
            }
            nav a {
                font-size: 20px; /* Agrandir les liens de navigation */
            }
            main {
                padding: 20px; /* Garder les espaces suffisants pour mobile */
            }
            form label {
                font-size: 20px; /* Agrandir les labels sur mobile */
            }
            form input, form select {
                font-size: 18px; /* Agrandir la taille des champs de saisie */
                padding: 15px;
            }
            form button {
                font-size: 18px; /* Agrandir le bouton */
                padding: 15px;
            }
        }

        /* Réduire la taille de la police sur les très petits écrans */
        @media (max-width: 480px) {
            header h1 {
                font-size: 24px; /* Taille du titre plus petite sur les très petits écrans */
            }
            nav a {
                font-size: 18px; /* Ajuste les tailles des liens pour les petits écrans */
            }
            form input, form select {
                font-size: 18px; /* Ajuste la taille des champs */
            }
            form button {
                font-size: 18px; /* Ajuste les boutons */
            }
        }

        /* Eviter le zoom sur les champs de formulaire */
        input, select, textarea {
            font-size: 16px;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>Créer un Utilisateur</h1>
    </header>
    <nav>
        <a href="dashboard.php">Tableau de bord</a>
        <a href="user.php">Créer Utilisateur</a>
        <a href="logout.php">Déconnexion</a>
    </nav>
    <main>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="role">Rôle :</label>
            <select id="role" name="role" required>
                <option value="coach">Coach</option>
                <option value="secretaire">Secrétaire</option>
            </select>

            <label for="pin_code">Code PIN :</label>
            <input type="password" id="pin_code" name="pin_code" required>

            <button type="submit">Créer l'utilisateur</button>
        </form>
    </main>
    <footer>
        <p>&copy; <?= date("Y"); ?> Salle de Sport. Tous droits réservés.</p>
    </footer>
</body>
</html>
