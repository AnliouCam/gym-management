<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Définir le mois et l'année à afficher (GET ou valeurs par défaut : mois courant)
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n'); // 'n' sans zéro initial
$year  = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Construire la date de début et de fin pour la requête
$startDate = sprintf("%04d-%02d-01", $year, $month);
$endDate   = date("Y-m-t", strtotime($startDate)); // dernier jour du mois

// Récupérer les visites de non-abonnés pour le mois sélectionné
$stmt = $pdo->prepare("SELECT * FROM non_abonnes WHERE DATE(date_heure) BETWEEN ? AND ? ORDER BY date_heure DESC");
$stmt->execute([$startDate, $endDate]);
$visites = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Création d'un tableau de liens pour chaque mois (exemple pour l'année en cours)
$months = range(1, 12);
$currentYear = $year; // possibilité de changer l'année via un formulaire
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visites Non-Abonnés - Gym Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .month-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
            padding: 20px;
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }
        .month-filter a {
            padding: 10px 20px;
            text-decoration: none;
            background: var(--bg-light);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            border: 2px solid var(--border-color);
            transition: var(--transition);
            font-weight: 600;
        }
        .month-filter a:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--text-white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .month-filter a.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--text-white);
            border-color: var(--primary-color);
        }
    </style>
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
            <h1>Visites Non-Abonnés</h1>
            <a href="add_non_abonne.php" class="btn btn-success">➕ Ajouter une Visite</a>
        </div>

        <div class="month-filter fade-in" style="animation-delay: 0.1s;">
            <?php
            $monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
            foreach($months as $m):
                $active = ($m == $month) ? "active" : "";
            ?>
                <a href="manage_non_abonnes.php?month=<?= $m ?>&year=<?= $currentYear ?>" class="<?= $active ?>">
                    <?= $monthNames[$m - 1] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="table-container fade-in" style="animation-delay: 0.2s;">
            <table>
                <thead>
                    <tr>
                        <th>Date de Visite</th>
                        <th>Montant Payé</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($visites)): ?>
                        <tr><td colspan="3" style="text-align: center; padding: 30px; color: var(--text-secondary);">Aucune visite enregistrée pour ce mois.</td></tr>
                    <?php else: ?>
                        <?php foreach ($visites as $visite): ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($visite['date_heure'])) ?></td>
                            <td><strong><?= number_format($visite['montant_paye'], 0, ',', ' ') ?> FCFA</strong></td>
                            <td>
                                <div class="btn-group">
                                    <a href="edit_non_abonne.php?id=<?= $visite['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                                    <a href="delete_non_abonne.php?id=<?= $visite['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?');">❌ Supprimer</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
