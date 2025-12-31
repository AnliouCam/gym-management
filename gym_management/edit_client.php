<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID du client est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de client invalide.";
    exit();
}

$client_id = intval($_GET['id']);

// Récupérer les informations du client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo "Client non trouvé.";
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $telephone = trim($_POST["telephone"]);
    $statut = trim($_POST["statut"]);

    if (empty($nom) || empty($telephone) || empty($statut)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("UPDATE clients SET nom = ?, telephone = ?, statut = ? WHERE id = ?");
        if ($stmt->execute([$nom, $telephone, $statut, $client_id])) {
            header("Location: manage_clients.php");
            exit();
        } else {
            $error = "Erreur lors de la mise à jour du client.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Modifier le Client - Gym Management</title>
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
            <li><a href="manage_clients.php" class="active"><span>📋</span> Gestion Clients</a></li>
            <li><a href="manage_abonnement.php"><span>📅</span> Gestion Abonnements</a></li>
            <li><a href="manage_paiements.php"><span>💰</span> Gestion Paiements</a></li>
            <li><a href="manage_users.php"><span>👤</span> Utilisateurs</a></li>
            <li><a href="manage_non_abonnes.php"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Modifier le Client</h1>
            <a href="manage_clients.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <?php if (!empty($error)): ?>
                <div class="error-message" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="nom">Nom complet *</label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="<?= htmlspecialchars($client['nom']) ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone *</label>
                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        value="<?= htmlspecialchars($client['telephone']) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="statut">Statut *</label>
                    <select id="statut" name="statut" required>
                        <option value="actif" <?= ($client['statut'] == 'actif') ? 'selected' : '' ?>>Actif</option>
                        <option value="inactif" <?= ($client['statut'] == 'inactif') ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Mettre à Jour
                    </button>
                    <a href="manage_clients.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
