<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Récupérer la liste des paiements avec le nom du client associé
$stmt = $pdo->query("SELECT p.id, c.nom AS client, p.montant, p.date_paiement 
                     FROM paiements p 
                     JOIN clients c ON p.client_id = c.id 
                     ORDER BY p.date_paiement DESC");
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Paiements - Gym Management</title>
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
            <h1>Gestion des Paiements</h1>
            <a href="add_paiement.php" class="btn btn-success">➕ Ajouter un Paiement</a>
        </div>

        <div class="table-container fade-in" style="animation-delay: 0.1s;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Montant (FCFA)</th>
                        <th>Date de Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><strong>#<?= htmlspecialchars($payment['id']); ?></strong></td>
                        <td><?= htmlspecialchars($payment['client']); ?></td>
                        <td><strong><?= number_format($payment['montant'], 0, ',', ' '); ?></strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($payment['date_paiement'])); ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_paiement.php?id=<?= $payment['id']; ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                                <a href="delete_paiement.php?id=<?= $payment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce paiement ?');">❌ Supprimer</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
