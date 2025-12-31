<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Ajouter un Client - Gym Management</title>
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
            <h1>Ajouter un Client</h1>
            <a href="manage_clients.php" class="btn btn-info">← Retour</a>
        </div>

        <div class="form-container fade-in" style="animation-delay: 0.1s;">
            <form action="add_client.php" method="POST">
                <div class="form-group">
                    <label for="nom">Nom complet *</label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        placeholder="Entrez le nom du client"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone *</label>
                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        placeholder="Ex: 77 123 45 67"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="abonnement">Type d'Abonnement *</label>
                    <select id="abonnement" name="abonnement" required>
                        <option value="">Sélectionner le type...</option>
                        <option value="mensuel">Mensuel</option>
                        <option value="trimestriel">Trimestriel</option>
                        <option value="annuel">Annuel</option>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-success">
                        ✅ Créer le Client
                    </button>
                    <a href="manage_clients.php" class="btn btn-danger">
                        ❌ Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="menu.js"></script>
</body>
</html>
