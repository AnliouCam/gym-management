<?php
session_start();
require_once "config.php"; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Récupérer les statistiques dynamiques
$totalClients = $pdo->query("
    SELECT COUNT(DISTINCT client_id) 
    FROM abonnements 
    WHERE statut = 'actif'
")->fetchColumn();

$totalRevenus = $pdo->query("
    SELECT COALESCE(SUM(montant), 0) 
    FROM paiements 
    WHERE DATE_FORMAT(date_paiement, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
")->fetchColumn();

$totalSeances = $pdo->query("
    SELECT COUNT(*) 
    FROM seances 
    WHERE DATE(date_heure) = CURDATE()
")->fetchColumn();

// Revenus des non-abonnés
$totalRevenusNonAbonnesMois = $pdo->query("
    SELECT COALESCE(SUM(montant_paye), 0) 
    FROM non_abonnes 
    WHERE DATE_FORMAT(date_heure, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
")->fetchColumn();

$totalRevenusNonAbonnesJour = $pdo->query("
    SELECT COALESCE(SUM(montant_paye), 0) 
    FROM non_abonnes 
    WHERE DATE(date_heure) = CURDATE()
")->fetchColumn();

// Récupérer les revenus des 6 derniers mois
$revenusMois = [];
$revenusNonAbonnesMois = [];
$moisLabels = [];

for ($i = 5; $i >= 0; $i--) {
    $mois = date("Y-m", strtotime("-$i months"));
    
    // Revenus des abonnés
    $revenu = $pdo->prepare("
        SELECT COALESCE(SUM(montant), 0) 
        FROM paiements 
        WHERE DATE_FORMAT(date_paiement, '%Y-%m') = ?
    ");
    $revenu->execute([$mois]);
    $revenusMois[] = $revenu->fetchColumn();

    // Revenus des non-abonnés
    $revenuNonAbonne = $pdo->prepare("
        SELECT COALESCE(SUM(montant_paye), 0) 
        FROM non_abonnes 
        WHERE DATE_FORMAT(date_heure, '%Y-%m') = ?
    ");
    $revenuNonAbonne->execute([$mois]);
    $revenusNonAbonnesMois[] = $revenuNonAbonne->fetchColumn();

    // Labels des mois
    $moisLabels[] = date("M", strtotime("-$i months"));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gym Management</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar slide-in">
        <div class="sidebar-header">
            <h2>GYM PRO</h2>
            <p>Gestion Moderne</p>
        </div>
        <ul>
            <li><a href="dashboard.php" class="active"><span>🏠</span> Tableau de Bord</a></li>
            <li><a href="manage_clients.php"><span>📋</span> Gestion Clients</a></li>
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
            <h1>Tableau de Bord</h1>
            <button class="btn btn-primary" onclick="window.location.href = 'rapport.php';">
                📄 Exporter Rapport
            </button>
        </div>

        <div class="stats-container">
            <div class="stat-box fade-in" style="animation-delay: 0.1s;">
                <div class="stat-icon">👥</div>
                <h3>Clients Abonnés</h3>
                <p><?= $totalClients; ?></p>
            </div>
            <div class="stat-box fade-in" style="animation-delay: 0.2s;">
                <div class="stat-icon">💰</div>
                <h3>Revenus Abonnés (Mois)</h3>
                <p><?= number_format($totalRevenus, 0, ',', ' '); ?> FCFA</p>
            </div>
            <div class="stat-box fade-in" style="animation-delay: 0.3s;">
                <div class="stat-icon">📊</div>
                <h3>Revenus Non-Abonnés (Mois)</h3>
                <p><?= number_format($totalRevenusNonAbonnesMois, 0, ',', ' '); ?> FCFA</p>
            </div>
        </div>

        <div class="chart-container fade-in" style="animation-delay: 0.4s;">
            <h2>📈 Évolution des Revenus</h2>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($moisLabels); ?>,
                datasets: [
                    {
                        label: 'Revenus Abonnés',
                        data: <?= json_encode($revenusMois); ?>,
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    },
                    {
                        label: 'Revenus Non-Abonnés',
                        data: <?= json_encode($revenusNonAbonnesMois); ?>,
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                weight: '600'
                            },
                            padding: 20
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
    <script src="menu.js"></script>
</body>
</html>
