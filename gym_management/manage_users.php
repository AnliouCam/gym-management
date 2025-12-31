<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté et s'il est coach (pour autoriser la gestion des utilisateurs)
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "coach") {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM utilisateurs");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Gym Management</title>
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
            <h1>Gestion des Utilisateurs</h1>
            <a href="user.php" class="btn btn-success">➕ Créer un Utilisateur</a>
        </div>

        <div class="table-container fade-in" style="animation-delay: 0.1s;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><strong>#<?= htmlspecialchars($user['id']); ?></strong></td>
                        <td><?= htmlspecialchars($user['nom']); ?></td>
                        <td>
                            <?php if ($user['role'] === 'coach'): ?>
                                <span class="badge badge-info">Coach</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Secrétaire</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn btn-warning btn-sm">✏ Modifier</a>
                                <a href="delete_user.php?id=<?= $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">❌ Supprimer</a>
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
