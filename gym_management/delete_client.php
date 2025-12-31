<?php
session_start();
require_once "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID du client est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID client invalide.";
    exit();
}

$client_id = intval($_GET['id']);

// Supprimer le client
$stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
if ($stmt->execute([$client_id])) {
    header("Location: manage_clients.php");
    exit();
} else {
    echo "Erreur lors de la suppression du client.";
}
?>
