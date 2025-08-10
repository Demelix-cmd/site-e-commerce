<?php
require_once 'config.php';

/**
 * Récupère tous les produits de la base de données
 * @param string $recherche - Terme de recherche optionnel
 * @return array - Tableau des produits
 */
function obtenirTousProduits($recherche = '') {
    global $pdo;
    
    try {
        if (!empty($recherche)) {
            $sql = "SELECT * FROM produits WHERE nom LIKE ? OR description LIKE ? ORDER BY date_ajout DESC";
            $stmt = $pdo->prepare($sql);
            $terme_recherche = '%' . $recherche . '%';
            $stmt->execute([$terme_recherche, $terme_recherche]);
        } else {
            $sql = "SELECT * FROM produits ORDER BY date_ajout DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }
        
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des produits : " . $e->getMessage());
        return [];
    }
}

/**
 * Récupère un produit par son ID
 * @param int $id - ID du produit
 * @return array|false - Données du produit ou false si non trouvé
 */
function obtenirProduitParId($id) {
    global $pdo;
    
    try {
        $sql = "SELECT * FROM produits WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch();
        
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération du produit : " . $e->getMessage());
        return false;
    }
}

/**
 * Supprime un produit de la base de données
 * @param int $id - ID du produit à supprimer
 * @return bool - True si suppression réussie, false sinon
 */
function supprimerProduit($id) {
    global $pdo;
    
    try {
        // Récupérer les informations du produit avant suppression
        $produit = obtenirProduitParId($id);
        
        if ($produit) {
            // Supprimer l'image du serveur si elle existe
            if (!empty($produit['image']) && file_exists('assets/uploads/' . $produit['image'])) {
                unlink('assets/uploads/' . $produit['image']);
            }
            
            // Supprimer le produit de la base de données
            $sql = "DELETE FROM produits WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            return true;
        }
        
        return false;
        
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression du produit : " . $e->getMessage());
        return false;
    }
}

/**
 * Formate le prix pour l'affichage
 * @param float $prix - Prix à formater
 * @return string - Prix formaté
 */
function formaterPrix($prix) {
    return number_format($prix, 2, ',', ' ') . ' CFA';
}

/**
 * Tronque un texte à une longueur donnée
 * @param string $texte - Texte à tronquer
 * @param int $longueur - Longueur maximale
 * @return string - Texte tronqué
 */
function tronquerTexte($texte, $longueur = 100) {
    if (strlen($texte) <= $longueur) {
        return $texte;
    }
    
    return substr($texte, 0, $longueur) . '...';
}

/**
 * Sécurise l'affichage d'une chaîne HTML
 * @param string $chaine - Chaîne à sécuriser
 * @return string - Chaîne sécurisée
 */
function securiserHTML($chaine) {
    return htmlspecialchars($chaine, ENT_QUOTES, 'UTF-8');
}
?>

