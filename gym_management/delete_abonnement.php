<?php
session_start();
require_once "config.php";

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID de l'abonnement est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID d'abonnement invalide.";
    exit();
}

$abonnement_id = intval($_GET['id']);

// Supprimer l'abonnement
$stmt = $pdo->prepare("DELETE FROM abonnements WHERE id = ?");
if ($stmt->execute([$abonnement_id])) {
    header("Location: manage_abonnement.php");
    exit();
} else {
    echo "Erreur lors de la suppression de l'abonnement.";
}
?>
