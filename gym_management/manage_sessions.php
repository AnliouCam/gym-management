<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Séances</title>

    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; }
        h2 { text-align: center; color: #333; }
        .btn { display: inline-block; padding: 10px 15px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #0056b3; }
        .session-list { margin-top: 20px; }
        .session { display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #e9ecef; border-radius: 5px; margin-bottom: 10px; }
        .session .actions { display: flex; gap: 10px; }
        .actions a { color: white; padding: 8px 12px; border-radius: 5px; text-decoration: none; }
        .edit { background: #28a745; }
        .delete { background: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestion des Séances</h2>
        <a href="#" class="btn">Ajouter une séance</a>       
        <div class="session-list">
            <div class="session">
                <span>📅 Séance Cardio - 10h00</span>
                <div class="actions">
                    <a href="#" class="edit">✏ Modifier</a>
                    <a href="#" class="delete">❌ Supprimer</a>
                </div>
            </div>
            <div class="session">
                <span>🏋️ Musculation - 14h00</span>
                <div class="actions">
                    <a href="#" class="edit">✏ Modifier</a>
                    <a href="#" class="delete">❌ Supprimer</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
