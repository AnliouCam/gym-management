<?php
require_once "config.php"; // Connexion à la base de données

// Agrégation des visites par jour
$sql = "
    INSERT INTO stats_non_abonnes (date_stat, total_visites, total_revenu)
    SELECT date_visite, COUNT(*), SUM(montant_paye)
    FROM non_abonnes
    WHERE date_visite = CURDATE() 
    GROUP BY date_visite
    ON DUPLICATE KEY UPDATE 
        total_visites = VALUES(total_visites), 
        total_revenu = VALUES(total_revenu);
";

if (mysqli_query($conn, $sql)) {
    echo "Agrégation des statistiques réussie.\n";
} else {
    echo "Erreur lors de l'agrégation : " . mysqli_error($conn) . "\n";
}

// Optionnel : Supprimer les données agrégées de la table `non_abonnes` pour éviter qu'elle ne devienne trop volumineuse.
$delete_old_data = "DELETE FROM non_abonnes WHERE date_visite = CURDATE()";
mysqli_query($conn, $delete_old_data);

mysqli_close($conn);
?>
