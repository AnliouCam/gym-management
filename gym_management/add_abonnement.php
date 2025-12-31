<?php
session_start();
require_once "config.php";

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Récupérer l'ID du client si passé en paramètre
$client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : 0;

// Récupération des clients pour l'ajout d'un abonnement

$stmt = $pdo->query("SELECT id, nom FROM clients WHERE statut = 'actif'");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST["client_id"];
    $type = $_POST["type"];
    $prix = $_POST["prix"];
    $date_debut = $_POST["date_debut"];
    $date_fin = date('Y-m-d', strtotime("+1 month", strtotime($date_debut)));
    
    if ($type == "trimestriel") {
        $date_fin = date('Y-m-d', strtotime("+3 months", strtotime($date_debut)));
    } elseif ($type == "annuel") {
        $date_fin = date('Y-m-d', strtotime("+1 year", strtotime($date_debut)));
    }
    
    $stmt = $pdo->prepare("INSERT INTO abonnements (client_id, type, prix, date_debut, date_fin) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$client_id, $type, $prix, $date_debut, $date_fin])) {
        header("Location: manage_abonnement.php");
        exit();
    } else {
        echo "Erreur lors de l'ajout de l'abonnement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Ajouter un Abonnement - Gym Management</title>
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
            <li><a href="manage_abonnement.php" class="active"><span>📅</span> Gestion Abonnements</a></li>
            <li><a href="manage_paiements.php"><span>💰</span> Gestion Paiements</a></li>
            <li><a href="manage_users.php"><span>👤</span> Utilisateurs</a></li>
            <li><a href="manage_non_abonnes.php"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Ajouter un Abonnement</h1>
            <a href="manage_abonnement.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <form action="add_abonnement.php" method="POST">
                <div class="form-group">
                    <label for="client_id">Client *</label>
                    <select id="client_id" name="client_id" required>
                        <option value="">Sélectionner un client...</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id'] ?>" <?= ($client['id'] == $client_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($client['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="type">Type d'Abonnement *</label>
                    <select id="type" name="type" required>
                        <option value="">Sélectionner le type...</option>
                        <option value="mensuel">Mensuel (1 mois)</option>
                        <option value="trimestriel">Trimestriel (3 mois)</option>
                        <option value="annuel">Annuel (12 mois)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="prix">Prix (FCFA) *</label>
                    <input
                        type="number"
                        id="prix"
                        name="prix"
                        placeholder="Entrez le prix"
                        min="0"
                        step="100"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="date_debut">Date de début *</label>
                    <input
                        type="date"
                        id="date_debut"
                        name="date_debut"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Créer l'Abonnement
                    </button>
                    <a href="manage_abonnement.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
