<?php
session_start();
require_once "config.php";

// Vérification que l'utilisateur est connecté et qu'il est coach
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "coach") {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID de l'utilisateur est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID utilisateur invalide.";
    exit();
}

$user_id = intval($_GET['id']);

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Utilisateur non trouvé.";
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
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, role = ?, pin_code = ? WHERE id = ?");
        if ($stmt->execute([$nom, $role, $pin_code, $user_id])) {
            header("Location: manage_users.php");
            exit();
        } else {
            $error = "Erreur lors de la mise à jour de l'utilisateur.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Modifier l'Utilisateur - Gym Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar slide-in">
        <div class="sidebar-header">
            <h2>GYM PRO</h2>
            <p>Gestion Moderne</p>
        </div>
        <ul>
            <li><a href="dashboard.php"><span>🏠</span> Tableau de Bord</a></li>
            <li><a href="manage_clients.php"><span>📋</span> Gestion Clients</a></li>
            <li><a href="manage_abonnement.php"><span>📅</span> Gestion Abonnements</a></li>
            <li><a href="manage_paiements.php"><span>💰</span> Gestion Paiements</a></li>
            <li><a href="manage_users.php" class="active"><span>👤</span> Utilisateurs</a></li>
            <li><a href="manage_non_abonnes.php"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Modifier l'Utilisateur</h1>
            <a href="manage_users.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <?php if (!empty($error)): ?>
                <div class="error-message" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="nom">Nom *</label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="<?= htmlspecialchars($user['nom']) ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="role">Rôle *</label>
                    <select id="role" name="role" required>
                        <option value="coach" <?= ($user['role'] == 'coach') ? 'selected' : '' ?>>Coach</option>
                        <option value="secretaire" <?= ($user['role'] == 'secretaire') ? 'selected' : '' ?>>Secrétaire</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="pin_code">Code PIN *</label>
                    <input
                        type="password"
                        id="pin_code"
                        name="pin_code"
                        value="<?= htmlspecialchars($user['pin_code']) ?>"
                        required
                    >
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Mettre à Jour
                    </button>
                    <a href="manage_users.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
