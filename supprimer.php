<?php
session_start();
require_once 'functions.php';

// Vérifier si un ID a été fourni
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Supprimer le produit
    if (supprimerProduit($id)) {
        $_SESSION['message_succes'] = "Produit supprimé avec succès !";
    } else {
        $_SESSION['message_erreur'] = "Erreur lors de la suppression du produit.";
    }
} else {
    $_SESSION['message_erreur'] = "ID de produit invalide.";
}

// Rediriger vers la page d'accueil
header('Location: index.php');
exit();
?>

