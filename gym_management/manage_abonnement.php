<?php
session_start();
require_once "config.php";

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Récupération des abonnements avec les informations du client
$stmt = $pdo->query("SELECT a.id, c.nom AS client, a.type, a.prix, a.date_debut, a.date_fin, a.statut
                     FROM abonnements a
                     JOIN clients c ON a.client_id = c.id
                     ORDER BY a.date_fin DESC");
$abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Abonnements - Gym Management</title>
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
            <h1>Gestion des Abonnements</h1>
            <a href="add_abonnement.php" class="btn btn-success">➕ Ajouter un Abonnement</a>
        </div>

        <div class="table-container fade-in" style="animation-delay: 0.1s;">
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Prix (FCFA)</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($abonnements as $abo): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($abo['client']) ?></strong></td>
                        <td>
                            <?php
                            $typeLabel = [
                                'mensuel' => 'Mensuel',
                                'trimestriel' => 'Trimestriel',
                                'annuel' => 'Annuel'
                            ];
                            echo '<span class="badge badge-info">' . ($typeLabel[$abo['type']] ?? $abo['type']) . '</span>';
                            ?>
                        </td>
                        <td><?= number_format($abo['prix'], 0, ',', ' ') ?></td>
                        <td><?= date('d/m/Y', strtotime($abo['date_debut'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($abo['date_fin'])) ?></td>
                        <td>
                            <?php if ($abo['statut'] === 'actif'): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Expiré</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_abonnement.php?id=<?= $abo['id'] ?>" class="btn btn-warning btn-sm">✏ Modifier</a>
                                <a href="delete_abonnement.php?id=<?= $abo['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement ?');">❌ Supprimer</a>
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
