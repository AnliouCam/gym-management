<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_non_abonnes.php?error=Aucune visite sélectionnée.");
    exit();
}

$id = intval($_GET["id"]);
$stmt = $pdo->prepare("SELECT * FROM non_abonnes WHERE id = ?");
$stmt->execute([$id]);
$visite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$visite) {
    header("Location: manage_non_abonnes.php?error=Visite introuvable.");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_heure = $_POST["date_heure"];
    $montant_paye = trim($_POST["montant_paye"]);

    if (empty($date_heure) || empty($montant_paye)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("UPDATE non_abonnes SET date_heure = ?, montant_paye = ? WHERE id = ?");
        if ($stmt->execute([$date_heure, $montant_paye, $id])) {
            header("Location: manage_non_abonnes.php?success=Visite mise à jour !");
            exit();
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Modifier une Visite - Gym Management</title>
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
            <li><a href="manage_non_abonnes.php" class="active"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Modifier une Visite Non-Abonnée</h1>
            <a href="manage_non_abonnes.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <?php if (!empty($error)): ?>
                <div class="error-message" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="date_heure">Date et Heure *</label>
                    <input
                        type="datetime-local"
                        id="date_heure"
                        name="date_heure"
                        value="<?= date('Y-m-d\TH:i', strtotime($visite['date_heure'])) ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="montant_paye">Montant Payé (FCFA) *</label>
                    <input
                        type="number"
                        id="montant_paye"
                        name="montant_paye"
                        value="<?= htmlspecialchars($visite['montant_paye']) ?>"
                        min="0"
                        step="100"
                        required
                    >
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Mettre à Jour
                    </button>
                    <a href="manage_non_abonnes.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
