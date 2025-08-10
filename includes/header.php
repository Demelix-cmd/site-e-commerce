<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($titre_page) ? $titre_page . ' - ' : ''; ?>REMA-SHOP</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="index.php">
                        <i class="fas fa-shopping-bag"></i>
                        <span>REMA-SHOP</span>
                    </a>
                </div>
                
                <nav class="nav-menu">
                    <ul>
                        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                            <i class="fas fa-home"></i> Accueil
                        </a></li>
                        <li><a href="ajouter.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'ajouter.php' ? 'active' : ''; ?>">
                            <i class="fas fa-plus"></i> Ajouter un produit
                        </a></li>
                        <li><a href="contact.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i> Contact
                        </a></li>
                        <li><a href="apropos.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'apropos.php' ? 'active' : ''; ?>">
                            <i class="fas fa-info-circle"></i> À propos
                        </a></li>
                        <li><a href="panier.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'panier.php' ? 'active' : ''; ?>">
                            <i class="fas fa-shopping-cart"></i> 
                            Panier 
                            <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
                                <span class="badge"><?php echo count($_SESSION['panier']); ?></span>
                            <?php endif; ?>
                        </a></li>
                    </ul>
                </nav>
                
                <div class="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </div>
    </header>
    
    <main class="main-content">
        <div class="container">
            <?php
            // Affichage des messages de succès
            if (isset($_SESSION['message_succes'])) {
                echo '<div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        ' . $_SESSION['message_succes'] . '
                      </div>';
                unset($_SESSION['message_succes']);
            }
            
            // Affichage des messages d'erreur
            if (isset($_SESSION['message_erreur'])) {
                echo '<div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        ' . $_SESSION['message_erreur'] . '
                      </div>';
                unset($_SESSION['message_erreur']);
            }
            
            // Affichage des erreurs de validation
            if (isset($_SESSION['erreurs'])) {
                echo '<div class="alert alert-error">';
                echo '<i class="fas fa-exclamation-triangle"></i>';
                echo '<ul>';
                foreach ($_SESSION['erreurs'] as $erreur) {
                    echo '<li>' . $erreur . '</li>';
                }
                echo '</ul>';
                echo '</div>';
                unset($_SESSION['erreurs']);
            }
            ?>

