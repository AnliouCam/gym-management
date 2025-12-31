<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID du paiement est passé en paramètre
if (!isset($_GET["id"])) {
    header("Location: manage_paiements.php");
    exit();
}

$id = $_GET["id"];

// Récupérer les informations du paiement
$stmt = $pdo->prepare("SELECT * FROM paiements WHERE id = ?");
$stmt->execute([$id]);
$paiement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paiement) {
    header("Location: manage_paiements.php");
    exit();
}

$error = "";

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $montant = trim($_POST["montant"]);
    $date_paiement = trim($_POST["date_paiement"]);

    if (empty($montant) || empty($date_paiement)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("UPDATE paiements SET montant = ?, date_paiement = ? WHERE id = ?");
        $result = $stmt->execute([$montant, $date_paiement, $id]);

        if ($result) {
            header("Location: manage_paiements.php");
            exit();
        } else {
            $error = "Erreur lors de la modification du paiement.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Modifier un Paiement - Gym Management</title>
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
            <li><a href="manage_paiements.php" class="active"><span>💰</span> Gestion Paiements</a></li>
            <li><a href="manage_users.php"><span>👤</span> Utilisateurs</a></li>
            <li><a href="manage_non_abonnes.php"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Modifier un Paiement</h1>
            <a href="manage_paiements.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <?php if (!empty($error)): ?>
                <div class="error-message" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="montant">Montant (FCFA) *</label>
                    <input
                        type="number"
                        id="montant"
                        name="montant"
                        value="<?= htmlspecialchars($paiement['montant']) ?>"
                        min="0"
                        step="100"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="date_paiement">Date de Paiement *</label>
                    <input
                        type="date"
                        id="date_paiement"
                        name="date_paiement"
                        value="<?= htmlspecialchars($paiement['date_paiement']) ?>"
                        required
                    >
                </div>

                <input type="hidden" name="id" value="<?= $paiement['id'] ?>">

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Mettre à Jour
                    </button>
                    <a href="manage_paiements.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
