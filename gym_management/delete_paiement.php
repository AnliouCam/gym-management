<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID du paiement est passé en paramètre et est numérique
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_paiements.php");
    exit();
}

$id = intval($_GET["id"]);

// Supprimer le paiement
$stmt = $pdo->prepare("DELETE FROM paiements WHERE id = ?");
if ($stmt->execute([$id])) {
    header("Location: manage_paiements.php?message=Paiement supprimé avec succès");
    exit();
} else {
    header("Location: manage_paiements.php?error=Erreur lors de la suppression du paiement");
    exit();
}
?>
