<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Récupérer les séances depuis la base de données
$stmt = $pdo->query("SELECT id, nom, date_heure, type, statut FROM seances");
$seances = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $seances[] = [
        'id' => $row['id'],
        'nom' => $row['nom'],
        'date_heure' => $row['date_heure'],
        'type' => $row['type'],
        'statut' => $row['statut'],
        'title' => $row['nom'] . ' - ' . date('H:i', strtotime($row['date_heure'])),
        'start' => $row['date_heure']
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Séances - Gym Management</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
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
            <li><a href="manage_non_abonnes.php"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php" class="active"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Gestion des Séances</h1>
            <a href="add_seance.php" class="btn btn-success">➕ Ajouter une Séance</a>
        </div>

        <div class="table-container fade-in" style="animation-delay: 0.1s;">
            <h2 class="mb-3">📋 Liste des Séances</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Date & Heure</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seances as $s): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($s['nom']) ?></strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($s['date_heure'])) ?></td>
                        <td>
                            <?php if ($s['type'] === 'groupe'): ?>
                                <span class="badge badge-info">Groupe</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Individuelle</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $statusClass = [
                                'prévu' => 'badge-info',
                                'réalisé' => 'badge-success',
                                'annulé' => 'badge-danger'
                            ];
                            $class = $statusClass[$s['statut']] ?? 'badge-info';
                            echo '<span class="badge ' . $class . '">' . htmlspecialchars(ucfirst($s['statut'])) . '</span>';
                            ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-warning btn-sm" href="edit_seance.php?id=<?= $s['id'] ?>">✏️ Modifier</a>
                                <a class="btn btn-danger btn-sm" href="delete_seance.php?id=<?= $s['id'] ?>" onclick="return confirm('Supprimer cette séance ?')">❌ Supprimer</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="chart-container fade-in" style="animation-delay: 0.2s;">
            <h2 class="mb-3">📅 Calendrier des Séances</h2>
            <div id="calendar"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: <?php echo json_encode($seances); ?>,
                eventColor: '#6366f1',
                eventBorderColor: '#4f46e5'
            });
            calendar.render();
        });
    </script>
    <script src="menu.js"></script>
</body>
</html>
