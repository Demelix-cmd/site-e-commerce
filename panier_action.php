<?php
session_start();
require_once 'functions.php';

// Initialiser le panier s'il n'existe pas
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Vérifier si une action a été demandée
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $response = ['success' => false, 'message' => ''];
    
    switch ($action) {
        case 'ajouter':
            if (isset($_POST['produit_id']) && is_numeric($_POST['produit_id'])) {
                $produit_id = intval($_POST['produit_id']);
                
                // Vérifier que le produit existe
                $produit = obtenirProduitParId($produit_id);
                if ($produit) {
                    // Ajouter au panier ou incrémenter la quantité
                    if (isset($_SESSION['panier'][$produit_id])) {
                        $_SESSION['panier'][$produit_id]['quantite']++;
                    } else {
                        $_SESSION['panier'][$produit_id] = [
                            'id' => $produit['id'],
                            'nom' => $produit['nom'],
                            'prix' => $produit['prix'],
                            'image' => $produit['image'],
                            'quantite' => 1
                        ];
                    }
                    
                    $response['success'] = true;
                    $response['message'] = 'Produit ajouté au panier';
                    $response['nombre_articles'] = count($_SESSION['panier']);
                } else {
                    $response['message'] = 'Produit introuvable';
                }
            } else {
                $response['message'] = 'ID de produit invalide';
            }
            break;
            
        case 'modifier':
            if (isset($_POST['produit_id']) && isset($_POST['quantite']) && 
                is_numeric($_POST['produit_id']) && is_numeric($_POST['quantite'])) {
                
                $produit_id = intval($_POST['produit_id']);
                $quantite = intval($_POST['quantite']);
                
                if (isset($_SESSION['panier'][$produit_id])) {
                    if ($quantite > 0) {
                        $_SESSION['panier'][$produit_id]['quantite'] = $quantite;
                        $response['success'] = true;
                        $response['message'] = 'Quantité mise à jour';
                    } else {
                        unset($_SESSION['panier'][$produit_id]);
                        $response['success'] = true;
                        $response['message'] = 'Produit retiré du panier';
                    }
                    $response['nombre_articles'] = count($_SESSION['panier']);
                } else {
                    $response['message'] = 'Produit non trouvé dans le panier';
                }
            } else {
                $response['message'] = 'Données invalides';
            }
            break;
            
        case 'supprimer':
            if (isset($_POST['produit_id']) && is_numeric($_POST['produit_id'])) {
                $produit_id = intval($_POST['produit_id']);
                
                if (isset($_SESSION['panier'][$produit_id])) {
                    unset($_SESSION['panier'][$produit_id]);
                    $response['success'] = true;
                    $response['message'] = 'Produit retiré du panier';
                    $response['nombre_articles'] = count($_SESSION['panier']);
                } else {
                    $response['message'] = 'Produit non trouvé dans le panier';
                }
            } else {
                $response['message'] = 'ID de produit invalide';
            }
            break;
            
        case 'vider':
            $_SESSION['panier'] = [];
            $response['success'] = true;
            $response['message'] = 'Panier vidé';
            $response['nombre_articles'] = 0;
            break;
            
        default:
            $response['message'] = 'Action non reconnue';
            break;
    }
    
    // Retourner la réponse en JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Si accès direct, rediriger vers le panier
header('Location: panier.php');
exit();
?>

