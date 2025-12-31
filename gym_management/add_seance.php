<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $date_heure = $_POST["date_heure"];
    $type = $_POST["type"];

    if (empty($nom) || empty($date_heure) || empty($type)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO seances (nom, date_heure, type, statut) VALUES (?, ?, ?, 'prévu')");
        if ($stmt->execute([$nom, $date_heure, $type])) {
            header("Location: manage_seance.php?success=Séance ajoutée !");
            exit();
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Ajouter une Séance - Gym Management</title>
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
            <li><a href="manage_users.php"><span>👤</span> Utilisateurs</a></li>
            <li><a href="manage_non_abonnes.php"><span>📊</span> Visites Non-Abonnées</a></li>
            <li><a href="manage_seance.php" class="active"><span>👥</span> Gestion Séances</a></li>
            <li><a href="logout.php"><span>🚪</span> Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header fade-in">
            <h1>Ajouter une Séance</h1>
            <a href="manage_seance.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <?php if (isset($error)): ?>
                <div class="error-message" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 15px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="nom">Nom de la Séance *</label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        placeholder="Ex: Yoga débutant, Cardio intense..."
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="date_heure">Date et Heure *</label>
                    <input
                        type="datetime-local"
                        id="date_heure"
                        name="date_heure"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="type">Type de Séance *</label>
                    <select id="type" name="type" required>
                        <option value="">Sélectionner le type...</option>
                        <option value="individuelle">Individuelle</option>
                        <option value="groupe">Groupe</option>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Créer la Séance
                    </button>
                    <a href="manage_seance.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
