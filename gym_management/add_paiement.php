<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->query("SELECT c.id, c.nom FROM clients c JOIN abonnements a ON c.id = a.client_id WHERE a.statut = 'actif'");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = trim($_POST["client_id"]);
    $montant = trim($_POST["montant"]);

    if (empty($client_id) || empty($montant)) {
        echo "Tous les champs sont obligatoires.";
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO paiements (client_id, montant) VALUES (?, ?)");
    if ($stmt->execute([$client_id, $montant])) {
        header("Location: manage_paiements.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout du paiement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Ajouter un Paiement - Gym Management</title>
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
            <h1>Ajouter un Paiement</h1>
            <a href="manage_paiements.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <form action="add_paiement.php" method="POST">
                <div class="form-group">
                    <label for="client_id">Client *</label>
                    <select id="client_id" name="client_id" required>
                        <option value="">Sélectionner un client...</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= htmlspecialchars($client['id']); ?>">
                                <?= htmlspecialchars($client['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="montant">Montant (FCFA) *</label>
                    <input
                        type="number"
                        id="montant"
                        name="montant"
                        placeholder="Entrez le montant"
                        min="0"
                        step="100"
                        required
                    >
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Enregistrer le Paiement
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
