<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_non_abonnes.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("DELETE FROM non_abonnes WHERE id = ?");
if ($stmt->execute([$id])) {
    header("Location: manage_non_abonnes.php?success=Visite supprimée !");
    exit();
} else {
    echo "Erreur lors de la suppression de la visite.";
}
?>
