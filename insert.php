<?php
session_start();
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Récupérer et nettoyer les données du formulaire
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);
    
    // Validation des données
    $erreurs = [];
    
    if (empty($nom)) {
        $erreurs[] = "Le nom du produit est obligatoire.";
    }
    
    if (empty($description)) {
        $erreurs[] = "La description du produit est obligatoire.";
    }
    
    if ($prix <= 0) {
        $erreurs[] = "Le prix doit être supérieur à 0.";
    }
    
    // Gestion de l'upload d'image
    $nom_image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        
        // Vérifier le type de fichier
        $types_autorises = ['image/jpeg', 'image/png', 'image/gif'];
        $type_fichier = $_FILES['image']['type'];
        
        if (!in_array($type_fichier, $types_autorises)) {
            $erreurs[] = "Seuls les fichiers JPEG, PNG et GIF sont autorisés.";
        }
        
        // Vérifier la taille du fichier (max 5MB)
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $erreurs[] = "La taille du fichier ne doit pas dépasser 5MB.";
        }
        
        if (empty($erreurs)) {
            // Générer un nom unique pour l'image
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $nom_image = uniqid() . '.' . $extension;
            $chemin_destination = 'assets/uploads/' . $nom_image;
            
            // Déplacer le fichier uploadé
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $chemin_destination)) {
                $erreurs[] = "Erreur lors de l'upload de l'image.";
            }
        }
    }
    
    // Si pas d'erreurs, insérer en base de données
    if (empty($erreurs)) {
        try {
            $sql = "INSERT INTO produits (nom, description, prix, image) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $description, $prix, $nom_image]);
            
            $_SESSION['message_succes'] = "Produit ajouté avec succès !";
            header('Location: index.php');
            exit();
            
        } catch (PDOException $e) {
            $erreurs[] = "Erreur lors de l'ajout du produit : " . $e->getMessage();
        }
    }
    
    // Stocker les erreurs en session pour les afficher
    if (!empty($erreurs)) {
        $_SESSION['erreurs'] = $erreurs;
        $_SESSION['form_data'] = $_POST; // Conserver les données du formulaire
        header('Location: ajouter.php');
        exit();
    }
}

// Si accès direct au fichier, rediriger vers la page d'ajout
header('Location: ajouter.php');
exit();
?>

