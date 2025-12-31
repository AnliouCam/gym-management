<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Récupérer la liste des clients
$clients = $pdo->query("SELECT * FROM clients")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients - Gym Management</title>
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
            <h1>Gestion des Clients</h1>
            <a href="add_client.php" class="btn btn-success">➕ Ajouter un Client</a>
        </div>

        <div class="table-container fade-in" style="animation-delay: 0.1s;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><strong>#<?= htmlspecialchars($client['id']) ?></strong></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['telephone']) ?></td>
                        <td>
                            <?php if ($client['statut'] === 'actif'): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_client.php?id=<?= $client['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                                <a href="delete_client.php?id=<?= $client['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">❌ Supprimer</a>
                                <a href="add_abonnement.php?client_id=<?= $client['id'] ?>" class="btn btn-info btn-sm">➕ Abonnement</a>
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
