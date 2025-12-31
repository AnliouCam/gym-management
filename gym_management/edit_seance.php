<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID de la séance est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID de séance invalide.";
    exit();
}

$seance_id = intval($_GET['id']);

// Récupérer les informations de la séance
$stmt = $pdo->prepare("SELECT * FROM seances WHERE id = ?");
$stmt->execute([$seance_id]);
$seance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seance) {
    echo "Séance non trouvée.";
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $date_heure = trim($_POST["date_heure"]);
    $type = trim($_POST["type"]);
    $statut = trim($_POST["statut"]);

    if (empty($nom) || empty($date_heure) || empty($type) || empty($statut)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("UPDATE seances SET nom = ?, date_heure = ?, type = ?, statut = ? WHERE id = ?");
        if ($stmt->execute([$nom, $date_heure, $type, $statut, $seance_id])) {
            header("Location: manage_seance.php?success=Séance mise à jour");
            exit();
        } else {
            $error = "Erreur lors de la mise à jour de la séance.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Modifier une Séance - Gym Management</title>
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
            <li><a href="manage_users.php"><span>👤</span> Utilisateurs</a></li>
            <li><a href="manage_non_abonnes.php"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php" class="active"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Modifier une Séance</h1>
            <a href="manage_seance.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <?php if (!empty($error)): ?>
                <div class="error-message" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="nom">Nom de la Séance *</label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="<?= htmlspecialchars($seance['nom']) ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="date_heure">Date et Heure *</label>
                    <input
                        type="datetime-local"
                        id="date_heure"
                        name="date_heure"
                        value="<?= date('Y-m-d\TH:i', strtotime($seance['date_heure'])) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="type">Type de Séance *</label>
                    <select id="type" name="type" required>
                        <option value="individuelle" <?= ($seance['type'] == 'individuelle') ? 'selected' : '' ?>>Individuelle</option>
                        <option value="groupe" <?= ($seance['type'] == 'groupe') ? 'selected' : '' ?>>Groupe</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="statut">Statut *</label>
                    <select id="statut" name="statut" required>
                        <option value="prévu" <?= ($seance['statut'] == 'prévu') ? 'selected' : '' ?>>Prévu</option>
                        <option value="réalisé" <?= ($seance['statut'] == 'réalisé') ? 'selected' : '' ?>>Réalisé</option>
                        <option value="annulé" <?= ($seance['statut'] == 'annulé') ? 'selected' : '' ?>>Annulé</option>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Mettre à Jour
                    </button>
                    <a href="manage_seance.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
