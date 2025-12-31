<?php
session_start();
require_once "config.php";

// Vérification que l'utilisateur est connecté et qu'il est coach
if (!isset($_SESSION["user"]) || $_SESSION["role"] != "coach") {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID de l'utilisateur est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID utilisateur invalide.";
    exit();
}

$user_id = intval($_GET['id']);

// Optionnel : empêcher la suppression de l'utilisateur connecté
if ($user_id == $_SESSION["user"]) {
    echo "Vous ne pouvez pas vous supprimer vous-même.";
    exit();
}

$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
if ($stmt->execute([$user_id])) {
    header("Location: manage_users.php");
    exit();
} else {
    echo "Erreur lors de la suppression de l'utilisateur.";
}
?>
