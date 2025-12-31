<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID de la séance est fourni
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_seance.php");
    exit();
}

$seance_id = intval($_GET["id"]);

// Supprimer la séance
$stmt = $pdo->prepare("DELETE FROM seances WHERE id = ?");
if ($stmt->execute([$seance_id])) {
    header("Location: manage_seance.php?success=Séance supprimée");
    exit();
} else {
    echo "Erreur lors de la suppression de la séance.";
}
?>
